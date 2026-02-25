import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;
import java.util.Random;

public class BombaServer {
    ServerSocket server = null;
    Socket client = null;

    BufferedReader in;
    DataOutputStream out;

    public static void main(String[] args) {
        BombaServer servente = new BombaServer();
        servente.attendi();
        servente.comunica();
    }

    public void attendi() {
        try {
            server = new ServerSocket(6791);
            System.out.println("SERVER BombaOrologeria in esecuzione sulla porta 6791...");

            client = server.accept();
            System.out.println("CLIENT connesso: " + client.getInetAddress());

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
            System.out.println("SERVER: socket server chiuso, inizio comunicazione...");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void comunica() {
        try {
            // Generazione della miccia con valore random tra 5 e 15
            Random rnd = new Random();
            int miccia = 5 + rnd.nextInt(11); // valore tra 5 e 15

            System.out.println("*** SERVER: BOMBA CREATA! Miccia iniziale = " + miccia + " ***");

            // Il server invia la bomba al client con il valore della miccia
            System.out.println("SERVER: invio la bomba al client. Miccia = " + miccia);
            out.writeBytes(miccia + "\n");

            // Loop: il server riceve la bomba dal client, riduce la miccia e la rispedisce
            while (true) {
                // Ricezione della bomba dal client
                String ricevuto = in.readLine();
                if (ricevuto == null) {
                    System.out.println("SERVER: connessione chiusa dal client.");
                    break;
                }

                // Controllo se il client ha notificato l'esplosione
                if (ricevuto.trim().equals("BOOM")) {
                    System.out.println("*** BOOM! La bomba e' esplosa sul CLIENT! ***");
                    break;
                }

                miccia = Integer.parseInt(ricevuto.trim());

                // Il server riduce la miccia
                miccia--;

                System.out.println("SERVER: ricevo la bomba dal client. Riduco la miccia -> Miccia = " + miccia);

                // Controllo se la bomba esplode
                if (miccia <= 0) {
                    System.out.println("*** BOOM! La bomba e' esplosa sul SERVER! ***");
                    // Notifica il client che la bomba e' esplosa
                    out.writeBytes("BOOM\n");
                    break;
                }

                // Il server rispedisce la bomba al client con la miccia ridotta
                System.out.println("SERVER: rispedisco la bomba al client. Miccia = " + miccia);
                out.writeBytes(miccia + "\n");
            }

            System.out.println("SERVER: chiudo la connessione con il client.");
            client.close();
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante la comunicazione...");
            System.exit(2);
        }
    }
}
