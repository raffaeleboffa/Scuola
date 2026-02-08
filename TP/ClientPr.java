import java.net.*;

public class ClientPr {
    Socket miosocket = null;
    String nomeServer = "localhost";
    int portaServer = 6789;

    public static void main(String[] args) {
        ClientPr cliente = new ClientPr();
        cliente.connetti();
        cliente.comunica();
    }

    public void connetti() {
        try {
            // miosocket = new Socket(nomeServer, portaServer);
            miosocket = new Socket(InetAddress.getLocalHost(), portaServer);
            System.out.println("CLIENT connesso ...");
        } catch(Exception e) {
            e.printStackTrace();
        }
    }

    public void comunica() {
        try {
            miosocket.close();
        } catch(Exception e) {
            e.printStackTrace();
        }
    }
}
