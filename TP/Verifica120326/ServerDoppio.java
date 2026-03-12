import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.ServerSocket;
import java.net.Socket;

public class ServerDoppio {
    ServerSocket server;
    Socket client;

    int port = 6789;

    BufferedReader in;
    DataOutputStream out;

    public boolean startServer() {
        try {
            server = new ServerSocket(port);
            System.out.println("Server aperto sulla porta: " + port);

            return true;
        } catch (IOException e) {
            System.out.println("Errore nell'apertura del server sulla porta: " + port);
            return false;
        }
    }

    public boolean attendiClient() {
        try {
            client = server.accept();
            System.out.println("Client connesso");

            in = new BufferedReader(new InputStreamReader(client.getInputStream())); // riceve dal client
            out = new DataOutputStream(client.getOutputStream()); // scrive al client
            
            return true;
        } catch (IOException e) {
            System.out.println("Errore nella connessione del client");
            return false;
        }
    }

    public void comunicazione() {
        try {
            int a = Integer.parseInt(in.readLine()) *2;
            System.out.println("Ricevuto 1° valore");
            int b = Integer.parseInt(in.readLine()) *2;
            System.out.println("Ricevuto 2° valore");
            int c = Integer.parseInt(in.readLine()) *2;
            System.out.println("Ricevuto 3° valore");

            out.writeBytes(Integer.toString(a+b+c) + "\n");
            System.out.println("Invio risultato: " + Integer.toString(a+b+c));

            server.close();
        } catch (NumberFormatException e) {
            System.out.println("Errore nella conversione String to Int");
        } catch (IOException e) {
            System.out.println("Errore nella lettura dei valori passati dal client o nella scrittura del risultato");
        }
    }

    public static void main(String[] args) {
        ServerDoppio s = new ServerDoppio();
        s.startServer();
        s.attendiClient();
        s.comunicazione();
    }
}