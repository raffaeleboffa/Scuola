import java.net.*;

public class ServerPr {
    ServerSocket server = null;
    Socket client = null;

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