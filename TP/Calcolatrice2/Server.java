import java.io.*;
import java.net.*;

public class Server {
    private int port = 50900;

    private ServerSocket server;
    private Socket client;

    private BufferedReader in;
    private DataOutputStream out;

    public static void main(String[] args) {
        Server server = new Server();
        try {
            server.attendi();
            server.comunica();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void attendi() throws IOException {
        server = new ServerSocket(port);
        System.out.println("Server in ascolto sulla porta " + port);

        client = server.accept();
        System.out.println("Client connesso: " + client.getInetAddress());

        in = new BufferedReader(new InputStreamReader(client.getInputStream()));
        out = new DataOutputStream(client.getOutputStream());
    }

    public void comunica() throws NumberFormatException, IOException {
        int a = Integer.parseInt(in.readLine());
        int b = Integer.parseInt(in.readLine());

        int sum = a + b;

        System.out.println("Ricevuti: " + a + " e " + b + ". Inviando somma: " + sum);

        out.writeBytes(sum + "\n");

        System.out.println("Somma inviata al client: " + sum);
    }
}