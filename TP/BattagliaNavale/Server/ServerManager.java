package BattagliaNavale.Server;

import BattagliaNavale.Server.Game.*;
import java.net.ServerSocket;
import java.util.ArrayList;

public class ServerManager implements Runnable {
    private ServerSocket serverInput = null;
    private ServerSocket serverOutput = null;

    private boolean serverRunning = false;
    private Thread threadServer;

    private ArrayList<Game> games = new ArrayList<>();

    public void startServer(int porta) {
        try {
            serverInput = new ServerSocket(porta);
            serverOutput = new ServerSocket(porta + 1);

            threadServer = new Thread(this);
            serverRunning = true;

            threadServer.start();
        } catch(Exception e) {
            System.out.println("Errore durante l'istanza del server");
            System.exit(1);
        }
    }

    public void stopServer() {
        try {
            serverRunning = false;

            for (Game game : games) {
                game.getLbl_status().setVisible(false);
            }

            serverInput.close();
            serverOutput.close();
        } catch(Exception e) {
            System.out.println("Errore durante la chiusura del server");
            System.exit(2);
        }
    }

    @Override
    public void run() {
        while(serverRunning) {
            games.add(new Game(games.size()));
            games.get(games.size() - 1).initializePlayers(serverInput, serverOutput);

            if (!serverRunning) break;

            try {
                Thread.sleep(2000);
            } catch (InterruptedException e) {
                System.out.println("Errore durante l'attesa per la creazione di una nuova partita");
            }
        }
        games.clear();
    }

    public ArrayList<Game> getGames() {
        return games;
    }
}
