import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

import javax.swing.JFrame;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import java.sql.ResultSet;

public class ShowingData extends JFrame  {
    public ShowingData(){
    
    	setSize(500,500);
    	setVisible(true);
    	
    	Connection conn = null;
    	Statement stmt = null;
    	ResultSet rs = null;
    	try {
    	    conn =
    	       DriverManager.getConnection("jdbc:mysql://127.0.0.1/test?user=root&password=");

    	    // Do something with the Connection
    	    stmt = conn.createStatement();
    	    rs = stmt.executeQuery("select * from samplelogin;");

    	    // loop over results
    	    
    	    String[][] data = new String[100][5];
    	    int counter = 0;
    	    
    	    while(rs.next()){
    	    	String id = rs.getString("id");      
    	       
    	    	data[counter][0] = id;
    	      
    	        String un = rs.getString("username");
    	    	data[counter][1] = un;    	      
    	        
    	        String pw = rs.getString("password");
    	    	data[counter][2] = pw;
    	    	
    	    	String em = rs.getString("email");      
     	       
    	    	data[counter][3] = em;
    	    	
    	    	String ag = rs.getString("age");      
     	       
    	    	data[counter][4] = ag;
    	        
    	        counter = counter + 1;
    	    }
    	    

    	    String[] colNames = {"id", "username", "password", "email", "age"};
    	    
    	    JTable table = new JTable(data, colNames);
    	    
    	    JScrollPane sr = new JScrollPane(table);
    	    
    	    this.add(sr);
    	    
    	    
    	} catch (SQLException ex) {
    	    // handle any errors
    	    System.out.println("SQLException: " + ex.getMessage());
    	    System.out.println("SQLState: " + ex.getSQLState());
    	    System.out.println("VendorError: " + ex.getErrorCode());
    	}
    	
    	
    	
    }
	public static void main(String[] args) {
		// TODO Auto-generated method stub
			new ShowingData();
	}

}
