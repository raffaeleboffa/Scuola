import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class EsClient {
    Socket miosocket = null;
    String nomeServer;
    int portaServer = 6789;

    BufferedReader in;
    DataOutputStream out;

    BufferedReader tastiera = new BufferedReader(new InputStreamReader(System.in));

    public EsClient() throws UnknownHostException {
        this.nomeServer = (String) InetAddress.getLocalHost().getHostAddress();
    }

    public static void main(String[] args) {
        try {
            EsClient cliente = new EsClient();
            cliente.connetti();
            cliente.comunica();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void connetti() {
        try {
            miosocket = new Socket(nomeServer, portaServer);
            System.out.println("CLIENT connesso al server " + nomeServer + ":" + portaServer);

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
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void comunica() {
        try {
            boolean continua = true;

            while (continua) {
                // Chiede all'utente di inserire una stringa da tastiera
                System.out.print("CLIENT: inserisci una stringa -> ");
                String stringa = tastiera.readLine();

                // Invia la stringa al server
                out.writeBytes(stringa + "\n");
                System.out.println("CLIENT: stringa inviata al server.");

                // Attende la risposta del server con il conteggio
                String risposta = in.readLine();
                System.out.println("CLIENT: risposta ricevuta -> " + risposta);

                // Parsing della risposta: formato "vocali=X consonanti=Y"
                // Estrae i valori numerici di vocali e consonanti
                String[] parti = risposta.split(" ");
                int vocali = Integer.parseInt(parti[0].split("=")[1]);
                int consonanti = Integer.parseInt(parti[1].split("=")[1]);

                System.out.println("CLIENT: vocali=" + vocali + ", consonanti=" + consonanti);

                // Controlla se il numero di consonanti e' esattamente la meta' del numero di vocali
                // cioe': consonanti == vocali / 2  =>  consonanti * 2 == vocali
                if (consonanti * 2 == vocali) {
                    System.out.println("CLIENT: condizione soddisfatta (consonanti = vocali/2). Chiudo la connessione.");
                    continua = false;
                } else {
                    System.out.println("CLIENT: condizione NON soddisfatta. Invio una nuova stringa...");
                }
            }

            // Chiude la connessione
            miosocket.close();
            System.out.println("CLIENT: connessione chiusa.");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
