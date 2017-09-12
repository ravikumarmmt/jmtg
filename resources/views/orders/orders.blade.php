<div style="background:#f2f2f2;margin:0 auto;max-width:640px;padding:0 20px"><div class="adM">
  </div><table align="center" border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div style="width:96%;margin:auto;padding:5px 0 0px 0"> <img src="http://104.199.217.141/images/logo.png" alt="My Taste Guide Logo" title="My Taste Guide Logo" width="80px" height="80px"> </div>
          <div style="background:#fff;color:#5b5b5b;border-radius:4px;font-family:arial;font-size:13px;padding:10px 50px;width:90%;margin:20px auto;line-height:17px;border:1px #ddd solid;border-top:0;clear:both">
            <p>Hi {{ucfirst($user->name)}}</p>
            <p>Thank you for your order #{{$order->id}} placed on {{$order_details->date}} with MTG</p>
            <p>Please find a summary and important next steps below.</p>
            <br/>
            <h2>Details</h2>
            <p><b>Plantype: </b> {{$order_details->plan_type}}</p>
            <p><b>Validity: </b> {{$order_details->validity}}</p>
            <p><b>Amount: </b> {{$order->amount}}
            @if ($order->currency != 'NULL')    
            {{$order->currency}}
            @endif
            </p>
            
            <p>{{$note}}</p>
            
            <br/>
            <p>Regards,</p>
            <p> MTG Care Team </p>
          </div>
         </td>
      </tr>
    </tbody>    
  </table>
</div>



