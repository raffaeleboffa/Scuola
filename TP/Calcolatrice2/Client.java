import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;

public class Client {
    public static void main(String[] args) {
        String host = "localhost";
        int port = 50900;

        Socket socket;

        BufferedReader in;
        DataOutputStream out;

        try {
            socket = new Socket(host, port);

            in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
            out = new DataOutputStream(socket.getOutputStream());

            int a = 5; 
            int b = 10;

            System.out.println("Invio numeri: " + a + " e " + b);

            out.writeBytes(a + "\n");
            out.writeBytes(b + "\n");

            String response = in.readLine();

            System.out.println("Risposta dal server: " + response);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}
