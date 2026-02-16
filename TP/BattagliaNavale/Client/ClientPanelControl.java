package BattagliaNavale.Client;

import java.awt.Graphics;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JTextField;

public class ClientPanelControl extends JPanel implements Runnable {
    private int width, height;
    private boolean isConnected = false;
    private ClientManager clientManager = new ClientManager();

    private Thread thread = new Thread(this);
    
    private JPanel setting, game;

    public ClientPanelControl(int width, int height) {
        addMouseListener(new MouseManager(clientManager));

        this.width = width;
        this.height = height;

        thread.start();
        setLayout(null);

        setting = new JPanel();
        setting.setLayout(null);
        setting.setBounds(0, 0, width, height);

        JLabel lbl_richiestaIP = new JLabel("Inserisci l'indirizzo IP del server:");
        lbl_richiestaIP.setBounds(10,10, 300, 30);
        setting.add(lbl_richiestaIP);

        JTextField txtField_IP = new JTextField();
        txtField_IP.setBounds(10, 40, 200, 30);
        setting.add(txtField_IP);

        JLabel lbl_richiestaPorta = new JLabel("Inserisci la porta del server:");
        lbl_richiestaPorta.setBounds(10,80, 300, 30);
        setting.add(lbl_richiestaPorta);

        JTextField txtField_Porta = new JTextField();
        txtField_Porta.setText("50900");
        txtField_Porta.setBounds(10, 110, 200, 30);
        setting.add(txtField_Porta);

        JButton btn_connetti = new JButton("Connetti");
        btn_connetti.addActionListener(e -> {
            String ip = txtField_IP.getText();
            int porta = Integer.parseInt(txtField_Porta.getText());
            clientManager.connetti(ip, porta);
        });
        btn_connetti.setBounds(10, 150, 100, 30);
        setting.add(btn_connetti);

        add(setting);

        game = new JPanel();
        // game.addMouseListener(new MouseManager());
    }

    @Override
    public void run() {
        while(true) {
            if(clientManager.isConnected() && !isConnected) {
                isConnected = true;
                remove(setting);
                add(game);
            }

            repaint();

            try {
                Thread.sleep(1000);
            } catch (InterruptedException e) {
                System.out.println("Errore durante l'attesa per la connessione al server");
            }
        }
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        if(isConnected) clientManager.drawMatrice(g);
    }
}
