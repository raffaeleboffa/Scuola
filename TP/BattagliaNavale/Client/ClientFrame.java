package BattagliaNavale.Client;

import javax.swing.JFrame;

public class ClientFrame extends JFrame {
    private int width = 850, height = 600;
    
    public ClientFrame() {
        setTitle("Client");
        setSize(width, height);
        setResizable(false);
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setLocationRelativeTo(null);
        add(new ClientPanelControl(width, height));
        setVisible(true);
    }

    public static void main(String[] args) {
        new ClientFrame();
    }
}
