<?php

class MailFetch{
	// imap server connection
	public $conn;

	// inbox storage and inbox message count
	private $inbox;
	public $msg_cnt;

	// email login credentials
	private $server;
	private $user;
	private $pass;
	private $port; // adjust according to server settings
	private $server_type;

	// connect to the server and get the inbox emails
	function __construct($server, $user, $pass, $port, $server_type) {
		$this->server = $server;
		$this->user = $user;
		$this->pass = $pass;
		$this->port = $port;
		$this->server_type = $server_type;

		$this->connect();
	}

	// close the server connection
	function close() {
		$this->inbox = array();
		$this->msg_cnt = 0;

		imap_close($this->conn);
	}

	// open the server connection
	// the imap_open function parameters will need to be changed for the particular server
	// these are laid out to connect to a Dreamhost IMAP server
	function connect() {
		$this->conn = imap_open("{".$this->server.":".$this->port."/".$this->server_type."}", $this->user, $this->pass);
	}

	// // move the message to a new folder
	// function move($msg_index, $folder='INBOX.Processed') {
	// 	// move on server
	// 	imap_mail_move($this->conn, $msg_index, $folder);
	// 	imap_expunge($this->conn);

	// 	// re-read the inbox
	// 	$this->inbox();
	// }

	// get a specific message (1 = first email, 2 = second email, etc.)
	function get($msg_index=NULL) {
		if (count($this->inbox) <= 0) {
			return array();
		}
		elseif ( ! is_null($msg_index) && isset($this->inbox[$msg_index])) {
			return $this->inbox[$msg_index];
		}

		return $this->inbox[0];
	}

	// read the inbox
	function inbox() {
		$msgs = imap_search($this->conn, 'ALL');
    	$no_of_msgs = $msgs ? count($msgs) : 0; 
		$messages = array();
	    for ($i = 0; $i < $no_of_msgs; $i++) {
	        $header = imap_header($this->conn, $msgs[$i]);
	        $from = $header->from;
	        foreach ($from as $id => $object) {
			    $fromaddress = $object->mailbox . "@" . $object->host;
			}

	        $subject = $this->mime_decode($header->subject);
	        
	        //Contenido del email
	        $body = imap_fetchbody($this->conn, $msgs[$i], "1.1");

			if ($body == "") {
			    $body = imap_fetchbody($this->conn, $msgs[$i], "1");
			}

			//Ficheros adjuntos
			$attachments = $this->email_attachmets($msgs[$i]);

			$body = trim(substr(quoted_printable_decode($body), 0, 1000));

	        array_push($messages, array('msg_no' => $msgs[$i],'from' => $fromaddress, 'subject' => $subject, 'body' => $body, 'attachments' => $attachments));
	    }
	    return $messages;
	}


	function format_html($str) {
	    // Convertit tous les caractères éligibles en entités HTML en convertissant les codes ASCII 10 en $lf
	    $str = htmlentities($str, ENT_COMPAT, "UTF-8");
	    $str = str_replace(chr(10), "<br>", $str);
	    return $str;
	}

	function mime_decode($text) {
       	$array = imap_mime_header_decode($text);
        $str = "";
        foreach ($array as $key => $part) {
            if($part->charset == "UTF-8"){
            	$str .= utf8_decode ($part->text);
            }else{
           		$str .= $part->text;
            }
        }

        return $str;
    }

    function email_attachmets($email_number){
		$structure = imap_fetchstructure($this->conn, $email_number);
		$attachments = array();
		if(isset($structure->parts) && count($structure->parts)) {
		 for($i = 0; $i < count($structure->parts); $i++) {
		   $attachments[$i] = array(
		      'is_attachment' => false,
		      'filename' => '',
		      'name' => '',
		      'attachment' => '');

		   if($structure->parts[$i]->ifdparameters) {
		     foreach($structure->parts[$i]->dparameters as $object) {
		       if(strtolower($object->attribute) == 'filename') {
		         $attachments[$i]['is_attachment'] = true;
		         $attachments[$i]['filename'] = $object->value;
		       }
		     }
		   }

		   if($structure->parts[$i]->ifparameters) {
		     foreach($structure->parts[$i]->parameters as $object) {
		       if(strtolower($object->attribute) == 'name') {
		         $attachments[$i]['is_attachment'] = true;
		         $attachments[$i]['name'] = $object->value;
		       }
		     }
		   }

		   if($attachments[$i]['is_attachment']) {
		     $attachments[$i]['attachment'] = imap_fetchbody($this->conn, $email_number, $i+1);
		     if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
		       $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
		     }
		     elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
		       $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
		     }
		   }             
		 } 
		}

		$adjuntos = array();

		 foreach($attachments as $attachment) {
            if($attachment['is_attachment'] == 1) {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];

                if(empty($filename)) $filename = time() . ".dat";

                $archivo = DIR_DOWNLOAD . $email_number . "_" . $filename;
                $fp = fopen($archivo, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
				
				$partes_ruta = pathinfo($archivo);
                
                $adjuntos[] = array(
                	'name' => str_replace("_", "", preg_replace('/[0-9]+/', '', $partes_ruta['filename'])),
                	'filename' => str_replace('\\', '/', $archivo)
                );
            }
        }

        return $adjuntos;
    }
}
?>