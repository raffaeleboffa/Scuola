package BattagliaNavale.Client;

import java.net.Socket;

public class ClientManager {
    public void connetti(String ip, int porta) {
        Socket socketInput = null;
        Socket socketOutput = null;

        do {
            try {
                socketInput = new Socket(ip, porta);
                socketOutput = new Socket(ip, porta+1);
            } catch (Exception e) {
                System.out.println("Errore durante la connessione al server, riprovo tra 5 secondi ...");
                try {
                    Thread.sleep(5000);
                } catch (InterruptedException ex) {
                    System.out.println("Errore durante l'attesa per la riconnessione al server");
                }
            }
        } while(socketInput == null || socketOutput == null);
    }
}
