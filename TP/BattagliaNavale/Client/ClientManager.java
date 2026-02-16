package BattagliaNavale.Client;

import java.awt.Color;
import java.awt.Graphics;
import java.net.Socket;

public class ClientManager {
    private Socket socketInput = null;
    private Socket socketOutput = null;

    private int[][] matriceAvversario = new int[10][10];
    private int[][] matricePersonale = new int[10][10];

    public ClientManager() {
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                matricePersonale[i][j] = 0;
                matriceAvversario[i][j] = 0;
            }
        }
    }

    public void connetti(String ip, int porta) {
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

    public boolean isConnected() {
        return socketInput != null && socketOutput != null;
    }

    public void drawMatrice(Graphics g) {
        g.setColor(Color.BLACK);
        g.drawString("Avversario", 450, 30);
        g.drawString("Tu", 50, 30);
        for (int i = 0; i < 10; i++) {
            for (int j = 0; j < 10; j++) {
                g.drawRect(50 + j*30, 50 + i*30, 30, 30);
                g.drawRect(450 + j*30, 50 + i*30, 30, 30);
            }
        }
    }
}
