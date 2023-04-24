<?php

namespace App\Helpers;

class EnumTextHelper {
	
	public static function getEnumText($enum){
		switch($enum){
			case 'CRT' 	: return 'Critical';
			case 'IMP' 	: return 'Important';
			case 'NOR' 	: return 'Normal';
			case 'LOW'	: return 'Low';
			case 'CMPLA': return 'Complaint';
			case 'CMPLI': return 'Compliment';
			case 'PEN'	: return 'Draft';
			case 'INP'	: return 'Inprogress';
			case 'ESC'	: return 'Escalated';
			case 'REP'	: return 'Replied';
			case 'REPP'	: return 'Replied-Draft';
			case 'COM'	: return 'Completed';
			case 'CLO'	: return 'Closed';
			case 'REJ'	: return 'Rejected';
			case 'ACP'	: return 'Accepted';
			case 'VFD'	: return 'Verified';
			case 'NACP'	: return 'Not Accepted';
			case 'BRN'  : return 'Branch';
			case 'DEPT' : return 'Dept';
			case 'SDEPT': return 'Sp. Dept';
			case 'HDEPT': return 'HO. Dept';
		}
	}
}