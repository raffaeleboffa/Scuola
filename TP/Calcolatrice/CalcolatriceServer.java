import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class CalcolatriceServer {
    ServerSocket server = null;
    Socket client = null;

    BufferedReader in;
    DataOutputStream out;

    public static void main(String[] args) {
        CalcolatriceServer servente = new CalcolatriceServer();
        servente.attendi();
        servente.comunica();
    }

    public void attendi() {
        try {
            server = new ServerSocket(6790);
            System.out.println("SERVER Calcolatrice in esecuzione sulla porta 6790...");

            client = server.accept();
            System.out.println("CLIENT connesso");

            // Creazione dello stream di INPUT per RICEVERE dati dal client
            // - client.getInputStream(): ottiene il flusso di byte in ingresso dal socket
            // - InputStreamReader: converte i byte in caratteri (gestisce la codifica)
            // - BufferedReader: aggiunge un buffer per leggere i dati in modo efficiente
            //   e permette di usare metodi come readLine() per leggere intere righe di testo
            in = new BufferedReader(new InputStreamReader(client.getInputStream()));

            // Creazione dello stream di OUTPUT per INVIARE dati al client
            // - client.getOutputStream(): ottiene il flusso di byte in uscita verso il client
            // - DataOutputStream: permette di scrivere dati primitivi Java (int, String, ecc.)
            //   in formato binario e fornisce metodi come writeUTF(), writeInt(), ecc.
            out = new DataOutputStream(client.getOutputStream());

            server.close();
            System.out.println("Server: chiuso socket server");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void comunica() {
        try {
            // Leggo la riga inviata dal client nel formato: "operando1 operatore operando2"
            String richiesta = in.readLine();
            System.out.println("Ricevuto dal client: " + richiesta);

            // Parsing della richiesta
            String[] parti = richiesta.split(" ");
            double operando1 = Double.parseDouble(parti[0]);
            String operatore = parti[1];
            double operando2 = Double.parseDouble(parti[2]);

            double risultato = 0;
            String errore = null;

            // Esecuzione dell'operazione richiesta
            switch (operatore) {
                case "+":
                    risultato = operando1 + operando2;
                    break;
                case "-":
                    risultato = operando1 - operando2;
                    break;
                case "*":
                    risultato = operando1 * operando2;
                    break;
                case "/":
                    if (operando2 == 0) {
                        errore = "Errore: divisione per zero";
                    } else {
                        risultato = operando1 / operando2;
                    }
                    break;
                default:
                    errore = "Errore: operatore non riconosciuto (" + operatore + ")";
                    break;
            }

            // Invio del risultato (o del messaggio di errore) al client
            if (errore != null) {
                System.out.println("Invio errore al client: " + errore);
                out.writeBytes(errore + "\n");
            } else {
                // Se il risultato è un numero intero, lo mostro senza decimali
                String risposta;
                if (risultato == (long) risultato) {
                    risposta = String.valueOf((long) risultato);
                } else {
                    risposta = String.valueOf(risultato);
                }
                System.out.println("Invio risultato al client: " + risposta);
                out.writeBytes(risposta + "\n");
            }

            System.out.println("Server: chiudo la connessione con il client");
            client.close();
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante la comunicazione ...");
            System.exit(2);
        }
    }
}
