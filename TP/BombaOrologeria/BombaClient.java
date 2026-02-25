import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.net.*;

public class BombaClient {
    Socket miosocket = null;
    String nomeServer;
    int portaServer = 6791;

    BufferedReader in;
    DataOutputStream out;

    public BombaClient() throws UnknownHostException {
        this.nomeServer = (String) InetAddress.getLocalHost().getHostAddress();
    }

    public static void main(String[] args) {
        try {
            BombaClient cliente = new BombaClient();
            cliente.connetti();
            cliente.comunica();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void connetti() {
        try {
            miosocket = new Socket(nomeServer, portaServer);
            System.out.println("CLIENT connesso al server BombaOrologeria...");

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

            System.out.println("CLIENT: in attesa della bomba dal server...");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void comunica() {
        try {
            // Loop: il client riceve la bomba dal server, riduce la miccia e la rispedisce
            while (true) {
                // Ricezione della bomba dal server
                String ricevuto = in.readLine();
                if (ricevuto == null) {
                    System.out.println("CLIENT: connessione chiusa dal server.");
                    break;
                }

                // Controllo se il server ha notificato l'esplosione
                if (ricevuto.trim().equals("BOOM")) {
                    System.out.println("*** BOOM! La bomba e' esplosa sul SERVER! ***");
                    break;
                }

                int miccia = Integer.parseInt(ricevuto.trim());

                // Il client riduce la miccia
                miccia--;

                System.out.println("CLIENT: ricevo la bomba dal server. Riduco la miccia -> Miccia = " + miccia);

                // Controllo se la bomba esplode
                if (miccia <= 0) {
                    System.out.println("*** BOOM! La bomba e' esplosa sul CLIENT! ***");
                    // Notifica il server che la bomba e' esplosa
                    out.writeBytes("BOOM\n");
                    break;
                }

                // Il client rispedisce la bomba al server con la miccia ridotta
                System.out.println("CLIENT: rispedisco la bomba al server. Miccia = " + miccia);
                out.writeBytes(miccia + "\n");
            }

            miosocket.close();
            System.out.println("CLIENT: connessione chiusa.");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
