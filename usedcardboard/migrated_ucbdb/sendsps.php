<?php  
require ("mainfunctions/database.php"); 
require ("mainfunctions/general-functions.php");
db();

$fp = fopen('files/sps.csv', 'w');
fwrite($fp, "PO NUMBER,FIRST NAME,MIDDLE NAME,LAST NAME,ADDRESSEE,ADDRESS 1,ADDRESS 2,CITY,STATE,ZIP,PHONE,PRICE LEVEL,EMAIL,ITEM NAME,ITEM,QTY" . "\r\n");
$query = "SELECT * FROM orders_sps WHERE sent = 0";
$res = db_query($query);
while ($row = array_shift($res)){

echo $row["order_string"] . "<BR>";

fwrite($fp, $row["order_string"] . "\r\n");
$res2 = db_query("UPDATE orders_sps SET sent=1 WHERE id = " . $row["id"]);


}
fclose($fp);

class AttachmentEmail {
	private string $from = 'spsordersnew@usedcardboardboxes.com';
	private string $from_name = 'UsedCardboardBoxes.com';
	private string $reply_to = 'spsordersnew@usedcardboardboxes.com';
	private string $to = '';
	private string $subject = '';
	private string $message = '';
	private string $attachment = '';
	private string $attachment_filename = '';

	public function __construct(string $to, string $subject, string $message, string $attachment = '',string $attachment_filename = '') {
		$this -> to = $to;
		$this -> subject = $subject;
		$this -> message = $message;
		$this -> attachment = $attachment;
		$this -> attachment_filename = $attachment_filename;
	}

	public function mail(): bool {
		if (!empty($this -> attachment)) {
			$filename = empty($this -> attachment_filename) ? basename($this -> attachment) : $this -> attachment_filename ;
			$path = dirname($this -> attachment);
			$mailto = $this -> to;
			$from_mail = $this -> from;
			$from_name = $this -> from_name;
			$replyto = $this -> reply_to;
			$subject = $this -> subject;
			$message = $this -> message;

			$file = $path.'/'.$filename;
			$file_size = filesize($file);
			$handle = fopen($file, "r");
			$content = fread($handle, $file_size);
			fclose($handle);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid((string)time()));
			$name = basename($file);
			$header = "From: ".$from_name." <".$from_mail.">\r\n";
			$header .= "Reply-To: ".$replyto."\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		
			$nmessage = "--".$uid."\r\n";
			$nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
			$nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$nmessage .= $message."\r\n\r\n";
			$nmessage .= "--".$uid."\r\n";
			$nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; 
			$nmessage .= "Content-Transfer-Encoding: base64\r\n";
			$nmessage .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			$nmessage .= $content."\r\n\r\n";
			$nmessage .= "--".$uid."--";
		
			if (mail($mailto, $subject, $nmessage, $header)) {
				//echo "Mail Sent Successfully to " . $mailto ."<br/>"; // or use booleans here
				return true;
			} else {
				return false;
			}
	
		} else {
			$header = "From: ".($this -> from_name)." <".($this -> from).">\r\n";
			$header .= "Reply-To: ".($this -> reply_to)."\r\n";

			if (mail($this -> to, $this -> subject, $this -> message, $header)) {
				return true;
			} else {
				return false;
			}

		}
	}
}
//spsordersNEW@usedcardboardboxes.com

$sendit = new AttachmentEmail('spsordersNEW@usedcardboardboxes.com', 'UsedCardboardBoxes.com Orders', '', 'files/sps.csv');
$sendit -> mail();
