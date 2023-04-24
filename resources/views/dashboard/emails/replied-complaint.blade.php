<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Complaint Handling System</title>


		<style>		
		     body {font-family: tahoma; color: #2d3138;}
		    .header-table {width: 100%;}
		    .header-bg {background-color: #FFFFFF;}
		    .main-table-two {padding: 15px; max-width: 600px;margin: 0 auto;display: block; border-radius: 0px;padding: 0px; border: 1px solid #fa6a2e;}
			.main-table-two-bg {width: 100%;background: #fa6a2e ;}
			.table-rowspan {text-align:center;padding:10px;}
			.img-side {float:left; width: 35%;}
			.header-tag {color:white;float:right;font-size: 13px;font-style: italic;margin-top: 20px; padding:10px; font-size: 14px; font-weight:normal;}
			.inner-table {padding: 10px;font-size:14px; width:100%;}
		    .inner-table-td {padding:10px;font-size:14px; width:100%;}
		    .subject-tag {font-size: 13px; font-weight: 700;}
		    .subject-para {font-size: 12px;}
		    .subject-para--two-link {font-size: 10px;}
		    .subject-para-three {font-size: 13px;}
		    .footer {font-size:12px; font-family: monospace; margin-top:20px; padding:5px; width:100%; background:#eee;}
		    .footer-tag{color:#333; text-decoration: none;}
		</style>


</head>
<body>
<div>
    <table class="header-table">
      <tr>
        <td></td>
        <td class="header-bg">
          <div class="main-table-two">
            <table class="main-table-two-bg">
              <tr>
                <td></td>
                <td>
                  <div>
                    <table width="100%">
                      <tr>
                        <td rowspan="2" class="table-rowspan">
							<img src="{{ $data->logo_url }}" class="img-side"/> 
							
<!-- 							<span class="header-tag"> "Its reliable its innovative its sampath it"<span></span></span></td> -->
                      </tr>
                    </table>
                  </div>
                </td>
                <td></td>
              </tr>
            </table>
            <table class="inner-table">
              <tr>
                <td class="inner-table-td">
                    <p class="subject-tag">Dear Sir/Madam,</p>
                    <p class="subject-para"><br />Above captioned CCER has been replied by {{$data->complaint_resolved_by}}</p>
                    <p class="subject-para"><br />Please access the {{config('app.name')}} System through below link.</p>
                    <p class="subject-para--two-link"><a href="{{$data->url}}" style="color:blue;font-size:12px;">Click Here</a></p>
                   
                    
                    <div class="clearfix">&nbsp;</div>                    
                    @if(isset($data->displayTestMails))
                    <div>{{$data->displayTestMails}}</div>
                    @endif
                    
                    <p class="subject-para">Thank You</p>
                    <p class="subject-para-three">{{config('app.name')}}</p>                     
                  <!-- FOOTER -->
                 </td>
              </tr>
			  <tr>
			  <td>
				 <div align="center" class="footer">
                   &copy; 2019 - {{$data->year}} <a href="http://www.sits.lk" target="_blank" class="footer-tag">Sampath Information Technology Solutions (PVT) Ltd</a>
                  </div>
                </td>
			  </tr>
            </table>
          </div>

</body>
</html>
