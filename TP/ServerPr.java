import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class ServerPr {
    ServerSocket server = null;
    Socket client = null;

    BufferedReader in;
    DataOutputStream out;

    public static void main(String[] args) {
        ServerPr servente = new ServerPr();
        servente.attendi();
        servente.comunica();
    }

    public void attendi() {
        try {
            server = new ServerSocket(6789);
            System.out.println("SERVER in esecuzione");

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

            System.out.println(in.readLine());

            server.close();
            System.out.println("Server: chiuso socket server");
        } catch(Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void comunica() {
        try {
            System.out.println("Server: chiudo la connessione con il client");
            client.close();
        } catch(Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante la connessione ...");
            System.exit(2);
        }
    }
}