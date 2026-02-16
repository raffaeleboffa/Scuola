package BattagliaNavale.Server.Game;

import java.net.ServerSocket;
import javax.swing.JLabel;

public class Game implements Runnable {
    private Player player1, player2;
    private int index;

    private Thread threadGame = new Thread(this);

    private JLabel lbl_status = new JLabel(); 

    public Game(int index) {
        this.index = index;
        threadGame.start();
    }

    public void initializePlayers(ServerSocket serverInput, ServerSocket serverOutput) {
        player1 = new Player(serverInput, serverOutput, index, 1);
        player2 = new Player(serverInput, serverOutput, index, 2);
    }

    public JLabel getLbl_status() {
        return lbl_status;
    }

    @Override
    public void run() {
        while (true) { 
            try {
                if (player1 != null && player2 != null) {
                    if (player1.isConnected() && player2.isConnected()) {
                        lbl_status.setText("Game " + index + ": entrambi i Player sono connessi");
                    } else if (player1.isConnected() ^ player2.isConnected()) {
                        lbl_status.setText("Game " + index + ": un Player è connesso, in attesa dell'altro Player...");
                    } else {
                        lbl_status.setText("Game " + index + ": nessun Player è connesso");
                    }
                } else if (player1 != null ^ player2 != null) {
                    lbl_status.setText("Game " + index + ": un Player potrebbe essere connesso");
                } else {
                    lbl_status.setText("Game " + index + ": nessun Player è connesso");
                }
                
                Thread.sleep(2000);
            } catch (InterruptedException e) {
                System.out.println("Errore durante l'attesa per la creazione di una nuova partita");
            }
        }
    }
}
