
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.InetAddress;
import java.net.Socket;
import java.net.UnknownHostException;

public class ClientDoppio {
    Socket socket = null;

    String server;
    int port = 6790;

    BufferedReader in;
    DataOutputStream out;

    BufferedReader tastiera;

    public boolean getLocalHost() {
        try {
            server = (String) InetAddress.getLocalHost().getHostAddress();
            return true;
        } catch (UnknownHostException e) {
            System.out.println("Errore nel reperire l'indirizzo host");
            return false;
        }
    }

    public boolean connetti() {
        try {
            socket = new Socket(server, port);
            System.out.println("Connesso al server");
            
            in = new BufferedReader(new InputStreamReader(socket.getInputStream())); // riceve dal server
            out = new DataOutputStream(socket.getOutputStream()); // scrive al server

            tastiera = new BufferedReader(new InputStreamReader(System.in));

            return true;
        } catch (UnknownHostException e) {
            System.out.println("Errore nella connessione: host sconosciuto");
            return false;
        } catch (IOException e) {
            System.out.println("Errore nella connessione al server");
            return false;
        }
    }

    public void comunica() {
        try {
            System.out.print("Inserisci il 1° numero: ");
            out.writeBytes(tastiera.readLine() + "\n");

            System.out.print("Inserisci il 2° numero: ");
            out.writeBytes(tastiera.readLine() + "\n");

            System.out.print("Inserisci il 3° numero: ");
            out.writeBytes(tastiera.readLine() + "\n");

            System.out.println("Risultato: " + in.readLine());
        } catch (NumberFormatException e) {
            System.out.println("Errore nella conversione ");
        } catch (IOException e) {
            System.out.println("Errore nell'invio dei messaggi");
        }
    }

    public static void main(String[] args) {
        ClientDoppio c = new ClientDoppio();
        if (c.getLocalHost()) if (c.connetti()) c.comunica();
    }
}
