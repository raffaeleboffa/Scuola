package BattagliaNavale.Server;

import java.net.ServerSocket;

public class ServerManager {
    ServerSocket serverInput = null;
    ServerSocket serverOutput = null;

    public void startServer(int porta) {
        try {
            serverInput = new ServerSocket(porta);
            serverOutput = new ServerSocket(porta + 1);
            System.out.println("SERVER in esecuzione sulla porta " + porta + " e sulla porta " + (porta + 1));
        } catch(Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void stopServer() {
        try {
            serverInput.close();
            serverOutput.close();
            System.out.println("Server: chiuso socket server");
        } catch(Exception e) {
            e.printStackTrace();
            System.out.println("Errore durante la chiusura del server");
            System.exit(2);
        }
    }
}
