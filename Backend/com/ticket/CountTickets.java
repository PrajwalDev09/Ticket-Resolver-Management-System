import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import java.sql.ResultSet;

public class CountTickets extends JFrame implements ActionListener {
    
	Connection conn = null;
	Statement stmt = null;
	ResultSet rs = null;
	
	
	
	public CountTickets(){

		
		
		try {
			
			  Class.forName("com.mysql.jdbc.Driver").newInstance();
			
			}catch(Exception e ){}
	
		
		try {
  	    conn =
  	       DriverManager.getConnection("jdbc:mysql://127.0.0.1/test?user=root&password=");

  	    // Do something with the Connection
  	    stmt = conn.createStatement();
//  	    rs = stmt.executeQuery("select * from tickets");
  	    
    	} catch (SQLException ex) {
    	    // handle any errors
    	    System.out.println("SQLException: " + ex.getMessage());
    	    System.out.println("SQLState: " + ex.getSQLState());
    	    System.out.println("VendorError: " + ex.getErrorCode());
    	}

    	
    }
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		new CountTickets();

			
	}
	

	public static void countOpenedTickets() throws SQLException {

		ResultSet rs = null;
		Statement stmt = null;
		    
		
	    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='opened'");
	        rs = stmt.getResultSet();
	        rs.next();
	        int count = rs.getInt("rowcount");

		    	    System.out.println("Tickets opened: " + count);
		    	

}

	public void countClosedTickets() throws SQLException {

        
    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='closed'");
        rs = stmt.getResultSet();
        rs.next();
        int count = rs.getInt("rowcount");

	    	    System.out.println("Tickets closed: " + count + "row(s).");
	    	

}
	@Override
	public void actionPerformed(ActionEvent e) {
		// TODO Auto-generated method stub
		
	}	
		
	
	
	
	
		
}


