import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class CalcolatriceClient {
    Socket miosocket = null;
    String nomeServer;
    int portaServer = 6790;

    BufferedReader in;
    DataOutputStream out;

    BufferedReader tastiera = new BufferedReader(new InputStreamReader(System.in));

    public CalcolatriceClient() throws UnknownHostException {
        this.nomeServer = (String) InetAddress.getLocalHost().getHostAddress();
    }

    public static void main(String[] args) {
        try {
            CalcolatriceClient cliente = new CalcolatriceClient();
            cliente.connetti();
            cliente.comunica();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void connetti() {
        try {
            miosocket = new Socket(nomeServer, portaServer);
            System.out.println("CLIENT connesso al server Calcolatrice...");

            // Creazione dello stream di INPUT per RICEVERE dati dal server
            // - miosocket.getInputStream(): ottiene il flusso di byte in ingresso dal socket
            // - InputStreamReader: converte i byte in caratteri (gestisce la codifica)
            // - BufferedReader: aggiunge un buffer per leggere i dati in modo efficiente
            //   e permette di usare metodi come readLine() per leggere intere righe di testo
            in = new BufferedReader(new InputStreamReader(miosocket.getInputStream()));

            // Creazione dello stream di OUTPUT per INVIARE dati al server
            // - miosocket.getOutputStream(): ottiene il flusso di byte in uscita verso il server
            // - DataOutputStream: permette di scrivere dati primitivi Java (int, String, ecc.)
            //   in formato binario e fornisce metodi come writeUTF(), writeInt(), ecc.
            out = new DataOutputStream(miosocket.getOutputStream());

            // Richiesta dei dati all'utente
            System.out.print("Inserisci il primo operando: ");
            String operando1 = tastiera.readLine();

            System.out.print("Inserisci l'operatore (+, -, *, /): ");
            String operatore = tastiera.readLine();

            System.out.print("Inserisci il secondo operando: ");
            String operando2 = tastiera.readLine();

            // Invio della richiesta al server nel formato: "operando1 operatore operando2"
            String richiesta = operando1 + " " + operatore + " " + operando2;
            System.out.println("Invio al server: " + richiesta);
            out.writeBytes(richiesta + "\n");

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void comunica() {
        try {
            // Ricezione del risultato dal server
            String risposta = in.readLine();
            System.out.println("Risultato ricevuto dal server: " + risposta);

            miosocket.close();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
