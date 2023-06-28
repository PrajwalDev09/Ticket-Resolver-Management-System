import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import java.sql.ResultSet;

public class ViewOpenedTickets extends JFrame implements ActionListener {
    
	Connection conn = null;
	Statement stmt = null;
	ResultSet rs = null;
	JLabel tx;
	Object count = null;
	

	

 	public ViewOpenedTickets() throws SQLException {
 		
 	
 		
 		setSize(300,300);
 		setVisible(true);
 		this.setLayout(new GridLayout(3,1));
			
			
			
	        
	    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='opened'");
	        rs = stmt.getResultSet();
	        rs.next();
	        
	        count = rs.getInt("rowcount");
	        System.out.println("Tickets opened: " + count);
	        
//    		JLabel opened = new JLabel("Tickets opened: " + count);
//    		this.add(opened);
    		

	
	}
 		
 	

			
			
		
		



		@Override
		public void actionPerformed(ActionEvent e) {
			// TODO Auto-generated method stub
			
		}

}
