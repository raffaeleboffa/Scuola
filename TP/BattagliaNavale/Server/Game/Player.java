package BattagliaNavale.Server.Game;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

public class Player {
    private Socket socketInput = null;
    private Socket socketOutput = null;

    public Player(ServerSocket serverInput, ServerSocket serverOutput, int indexGame, int indexPlayer) {
        try {
            socketInput = serverInput.accept();
            socketOutput = serverOutput.accept();
        } catch (IOException e) {
            System.out.println("Errore nella connessione con il Player " + indexPlayer + " nel game " + indexGame);
        }
    }

    public boolean isConnected() {
        return socketInput != null && socketOutput != null && socketInput.isConnected() && socketOutput.isConnected();
    }
}
