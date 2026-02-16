package BattagliaNavale.Client;

import javax.swing.JFrame;

public class ClientFrame extends JFrame {
    public ClientFrame() {
        setTitle("Client");
        setSize(500, 500);
        setResizable(false);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        add(new ClientPanelControl());
        setVisible(true);
    }

    public static void main(String[] args) {
        new ClientFrame();
    }
}
