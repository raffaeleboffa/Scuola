import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class EsServer {
    ServerSocket server = null;
    Socket client = null;

    BufferedReader in;
    DataOutputStream out;

    public static void main(String[] args) {
        EsServer servente = new EsServer();
        servente.attendi();
        servente.comunica();
    }

    public void attendi() {
        try {
            server = new ServerSocket(6789);
            System.out.println("SERVER in esecuzione sulla porta 6789...");

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
            System.out.println("SERVER: socket server chiuso, in attesa di messaggi...");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void comunica() {
        try {
            String stringa;

            // Loop: il server rimane in ascolto finche' il client non chiude la connessione
            while ((stringa = in.readLine()) != null) {
                System.out.println("SERVER: stringa ricevuta -> \"" + stringa + "\"");

                // Conteggio vocali e consonanti
                int vocali = 0;
                int consonanti = 0;
                String stringaLower = stringa.toLowerCase();

                for (int i = 0; i < stringaLower.length(); i++) {
                    char c = stringaLower.charAt(i);
                    if (Character.isLetter(c)) {
                        if (c == 'a' || c == 'e' || c == 'i' || c == 'o' || c == 'u') {
                            vocali++;
                        } else {
                            consonanti++;
                        }
                    }
                }

                System.out.println("SERVER: vocali=" + vocali + " consonanti=" + consonanti);

                // Invio del risultato al client nel formato "vocali=X consonanti=Y"
                out.writeBytes("vocali=" + vocali + " consonanti=" + consonanti + "\n");
            }

            System.out.println("SERVER: il client ha chiuso la connessione.");
            client.close();
            System.out.println("SERVER: connessione chiusa.");
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante la comunicazione...");
            System.exit(2);
        }
    }
}
