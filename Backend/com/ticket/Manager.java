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

public class Manager extends JFrame implements ActionListener {
    
	Connection conn = null;
	Statement stmt = null;
	ResultSet rs = null;
	JLabel opened;
	JLabel closed;
	int count;
	
	
	public Manager() throws SQLException{
    	
    	
    	setSize(300,300);
		setVisible(true);
		this.setLayout(new GridLayout(3,1));
		
		JButton ref = new JButton("Refresh");
		ref.addActionListener(this);
		// we are adding on a label to the button here
		// so later we will know which button has been clicked.
		ref.setActionCommand("View Tickets");
		this.add(ref);
		

		
		
		
		
		
		try {
			
			  Class.forName("com.mysql.jdbc.Driver").newInstance();
			
			}catch(Exception e ){
				
			}
	
		
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

    	
    

	
		
		
		
		
			try {
	    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='opened'");
	        rs = stmt.getResultSet();
	        rs.next();
	        
	        count = rs.getInt("rowcount");
	        opened = new JLabel("Tickets opened: " + count);
	        this.add(opened);
		    
	        System.out.println("Tickets opened: " + count + "row(s).");
	        
			} catch (SQLException ex) {
	    	    // handle any errors
	    	    System.out.println("SQLException: " + ex.getMessage());
	    	    System.out.println("SQLState: " + ex.getSQLState());
	    	    System.out.println("VendorError: " + ex.getErrorCode());
	    	}
			
					
					
	

		    	


	try {

        
    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='closed'");
        rs = stmt.getResultSet();
        rs.next();
        int count = rs.getInt("rowcount");

	    	    
        closed = new JLabel("Tickets closed: " + count);
        this.add(closed);
	    
        System.out.println("Tickets closed: " + count + "row(s).");
        
		} catch (SQLException ex) {
    	    // handle any errors
    	    System.out.println("SQLException: " + ex.getMessage());
    	    System.out.println("SQLState: " + ex.getSQLState());
    	    System.out.println("VendorError: " + ex.getErrorCode());
    	}
	    	    
				
}			

	
	public static void main(String[] args) throws SQLException {
		// TODO Auto-generated method stub
			new Manager();
			
	}
		
	public void RefreshButton () {
		try {
			
			  Class.forName("com.mysql.jdbc.Driver").newInstance();
			
			}catch(Exception e ){
				
			}
	
		
		try {
	    conn =
	       DriverManager.getConnection("jdbc:mysql://127.0.0.1/test?user=root&password=");

	    // Do something with the Connection
	    stmt = conn.createStatement();
//	    rs = stmt.executeQuery("select * from tickets");
	    
  	} catch (SQLException ex) {
  	    // handle any errors
  	    System.out.println("SQLException: " + ex.getMessage());
  	    System.out.println("SQLState: " + ex.getSQLState());
  	    System.out.println("VendorError: " + ex.getErrorCode());
  	}

  	
  

	
		
		
		
		
			try {
	    	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='opened'");
	        rs = stmt.getResultSet();
	        rs.next();
	        
	        count = rs.getInt("rowcount");
	        opened = new JLabel("Tickets opened: " + count);
	        this.add(opened);
		    
	        System.out.println("Tickets opened: " + count + "row(s).");
	        
			} catch (SQLException ex) {
	    	    // handle any errors
	    	    System.out.println("SQLException: " + ex.getMessage());
	    	    System.out.println("SQLState: " + ex.getSQLState());
	    	    System.out.println("VendorError: " + ex.getErrorCode());
	    	}
			
					
					
	

		    	


	try {

      
  	rs = stmt.executeQuery("SELECT COUNT(*) AS rowcount FROM tickets WHERE status ='closed'");
      rs = stmt.getResultSet();
      rs.next();
      int count = rs.getInt("rowcount");

	    	    
      closed = new JLabel("Tickets closed: " + count);
      this.add(closed);
	    
      System.out.println("Tickets closed: " + count + "row(s).");
      
		} catch (SQLException ex) {
  	    // handle any errors
  	    System.out.println("SQLException: " + ex.getMessage());
  	    System.out.println("SQLState: " + ex.getSQLState());
  	    System.out.println("VendorError: " + ex.getErrorCode());
  	}
	    	    
				
}	
	
	
	
	
	@Override
	public void actionPerformed(ActionEvent e) {
		if(e.getActionCommand().equals("Refresh")){
			this.RefreshButton();
			
			}
		}
		
			

			
			
}
		
	


