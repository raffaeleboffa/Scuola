package BattagliaNavale.Server;

import BattagliaNavale.Server.Game.Game;
import java.awt.Color;
import java.net.InetAddress;
import javax.swing.*;

public class ServerPanelControl extends JPanel implements Runnable {
    private int porta;
    private String nomeServer;

    private Boolean serverAttivo = false;
    private Thread threadServer;

    private JLabel lbl_richiestaPorta, lbl_showIP, lbl_status;
    private JTextField txtField_porta;
    private JButton btn_getPorta;

    private ServerManager serverManager = new ServerManager();

    public ServerPanelControl() {
        setLayout(null);

        try {
            nomeServer = InetAddress.getLocalHost().getHostAddress();
        } catch (Exception e) {
            System.out.println("Errore durante l'ottenimento dell'indirizzo IP del server");
            System.exit(1);
        }

        lbl_richiestaPorta = new JLabel("Inserisci la porta su cui avviare il server:");
        lbl_richiestaPorta.setBounds(10,10, 300, 30);
        add(lbl_richiestaPorta);

        txtField_porta = new JTextField();
        txtField_porta.setText("50900");
        txtField_porta.setBounds(10, 40, 200, 30);
        add(txtField_porta);

        btn_getPorta = new JButton("Start");
        btn_getPorta.setBounds(220, 40, 80, 30);
        btn_getPorta.addActionListener(e -> {
            if(!serverAttivo) {
                btn_getPorta.setText("Stop");
                lbl_status.setText("Server avviato sulla porta " + getPorta() + " e sulla porta " + (getPorta()+1));
                lbl_status.setForeground(Color.getHSBColor(0.33f, 1.0f, 0.5f));
                serverManager.startServer(getPorta());
            } else {
                btn_getPorta.setText("Start");
                lbl_status.setText("Server non avviato");
                lbl_status.setForeground(Color.RED);
                serverManager.stopServer();
            }
            porta = getPorta();
            serverAttivo = !serverAttivo;
        });
        add(btn_getPorta);

        lbl_showIP = new JLabel("Indirizzo IP del server: " + nomeServer);
        lbl_showIP.setBounds(10, 80, 300, 30);
        add(lbl_showIP);

        lbl_status = new JLabel("Server non avviato");
        lbl_status.setBounds(10, 110, 300, 30);
        lbl_status.setForeground(Color.RED);
        add(lbl_status);

        threadServer = new Thread(this);
        threadServer.start();
    }

    public int getPorta() {
        return Integer.parseInt(txtField_porta.getText());
    }

    @Override
    public void run() {
        while(true) {
            for (Game game : serverManager.getGames()) {
                add(game.getLbl_status());
                game.getLbl_status().setBounds(10, 140 + serverManager.getGames().indexOf(game) * 30, 300, 30);
            }

            try {
                Thread.sleep(2000);
            } catch (InterruptedException e) {
                System.out.println("Errore durante l'attesa per l'aggiornamento dello stato del server");
            }
        }
    }
}
