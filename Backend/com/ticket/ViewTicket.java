import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import java.sql.ResultSet;

public class ViewTicket extends JFrame implements ActionListener {
    public ViewTicket(){
    
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
    	    rs = stmt.executeQuery("select * from tickets");

    	    // loop over results
    	    
    	    Object[][] data = new Object[100][9];
    	    int counter = 0;
    	    
    	    while(rs.next()){
    	    	String id = rs.getString("id");      
    	    	data[counter][0] = id;
    	      
    	        String cId = rs.getString("clientid");
    	    	data[counter][1] = cId;    	      
    	        
    	        String desc = rs.getString("description");
    	    	data[counter][2] = desc;
    	    	
    	    	String sev = rs.getString("sev");      
    	    	data[counter][3] = sev;
    	    	
    	    	String opTime = rs.getString("opentime");      
    	    	data[counter][4] = opTime;
    	    	
    	    	String clTime = rs.getString("closetime");      
    	    	data[counter][5] = clTime;
    	    	
    	    	String ass = rs.getString("assigned");      
    	    	data[counter][6] = ass;
    	    	
    	    	String st = rs.getString("status");   
    	    	data[counter][7] = st;
    	    	
    	    	JButton insert = new JButton("Insert");
    			insert.addActionListener(this);
    			add(insert);
    			data[counter][8] = insert;
    	    	

    	        
    	        counter = counter + 1;
    	        
  
    	    }
		
    	    

    	    Object[] colNames = {"id", "clientid","description","sev", "opentime", "closetime", "assigned", "status", ""};
    	    
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
			new ViewTicket();

	}
	
	
	public void deleteTicket (){
		
		
		
	}
	@Override
	public void actionPerformed(ActionEvent e) {
		// TODO Auto-generated method stub
		
	}

}

