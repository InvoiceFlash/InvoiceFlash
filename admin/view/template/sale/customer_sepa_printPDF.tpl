<!doctype html>
<html dir="<?php echo $direction; ?>">
<head>
<meta charset="utf-8">
<base href="<?php echo $base; ?>">
<style>
* { padding: 0; margin: 0; font-family: helvetica; }
body { font-size: 13px; }
.title { text-align: center; font-size: 22px; font-weight: bold; padding-bottom: 6px; }
.subtitle { text-align: center; font-size: 15px; font-style: italic; padding-bottom: 20px; }
table.box { width: 100%; border: 1px solid #000000; border-collapse: collapse; margin-bottom: 18px; }
table.box td { border: 1px solid #000000; padding: 10px 12px; vertical-align: top; }
.section-title { background-color: #dee2e6; font-weight: bold; padding: 7px 12px; font-size: 14px; }
.label { font-weight: bold; font-size: 12px; }
.sublabel { font-style: italic; font-size: 10px; color: #333333; }
.value { font-size: 15px; padding-top: 4px; display: block; }
.legal { font-size: 10px; text-align: justify; padding: 10px 0; line-height: 1.5; }
.legal-en { font-size: 10px; font-style: italic; text-align: justify; padding-bottom: 16px; line-height: 1.5; }
.legal-center { font-size: 10px; text-align: center; line-height: 1.6; padding-top: 6px; }
</style>
</head>
<body>
<div class="title">Orden de domiciliación de adeudo directo SEPA B2B</div>
<div class="subtitle">SEPA Business-to-Business Direct Debit Mandate</div>

<table class="box">
<tr><td colspan="2" class="section-title">A cumplimentar por el acreedor / To be completed by the creditor</td></tr>
<tr>
	<td width="50%">
		<span class="label">Referencia de la orden de domiciliación</span><br>
		<span class="sublabel">Mandate reference</span><br>
		<span class="value"><?php echo $mandate_reference; ?></span>
	</td>
	<td width="50%">
		<span class="label">Identificador del acreedor</span><br>
		<span class="sublabel">Creditor Identifier</span><br>
		<span class="value"><?php echo $creditor_id; ?></span>
	</td>
</tr>
<tr>
	<td colspan="2">
		<span class="label">Nombre del acreedor</span><br>
		<span class="sublabel">Creditor's name</span><br>
		<span class="value"><?php echo $creditor_name; ?></span>
	</td>
</tr>
<tr>
	<td colspan="2">
		<span class="label">Dirección</span><br>
		<span class="sublabel">Address</span><br>
		<span class="value"><?php echo $creditor_address; ?></span>
	</td>
</tr>
</table>

<div class="legal">
Mediante la firma de esta orden de domiciliación, el deudor autoriza (A) al acreedor a enviar instrucciones a la entidad del deudor para adeudar su cuenta y (B) a la entidad para efectuar los adeudos en su cuenta siguiendo las instrucciones del acreedor. Esta orden de domiciliación está prevista para operaciones exclusivamente entre empresas y/o autónomos. El deudor no tiene derecho a que su entidad le reembolse una vez que se haya realizado el cargo en cuenta, pero puede solicitar a su entidad que no efectúe el adeudo en la cuenta hasta la fecha debida. Podrá obtener información detallada del procedimiento en su entidad financiera.
</div>
<div class="legal-en">
By signing this mandate form, you authorise (A) the Creditor to send instructions to your bank to debit your account and (B) your bank to debit your account in accordance with the instructions from the Creditor. This mandate is only intended for business-to-business transactions. You are not entitled to a refund from your bank after your account has been debited, but you are entitled to request your bank not to debit your account up until the day on which the payment is due. Please contact your bank for detailed procedures in such a case.
</div>

<table class="box">
<tr><td colspan="2" class="section-title">A cumplimentar por el deudor / To be completed by the debtor</td></tr>
<tr>
	<td colspan="2">
		<span class="label">Nombre del deudor/es (titular/es de la cuenta de cargo)</span><br>
		<span class="sublabel">Debtor's name</span><br>
		<span class="value"><?php echo $debtor_name; ?></span>
	</td>
</tr>
<tr>
	<td colspan="2">
		<span class="label">Dirección del deudor</span><br>
		<span class="sublabel">Address of the debtor</span><br>
		<span class="value"><?php echo $debtor_address; ?></span>
	</td>
</tr>
<tr>
	<td width="65%">
		<span class="label">Código postal - Población - Provincia</span><br>
		<span class="sublabel">Postal Code - City - Town</span><br>
		<span class="value"><?php echo trim($debtor_postcode . ' - ' . $debtor_city . ' - ' . $debtor_province, ' -'); ?></span>
	</td>
	<td width="35%">
		<span class="label">País</span><br>
		<span class="sublabel">Country</span><br>
		<span class="value"><?php echo $debtor_country; ?></span>
	</td>
</tr>
<tr>
	<td width="50%">
		<span class="label">Swift BIC</span><br>
		<span class="sublabel">up to 8 or 11 characters</span><br>
		<span class="value"><?php echo $debtor_bic; ?></span>
	</td>
	<td width="50%">
		<span class="label">Número de cuenta - IBAN</span><br>
		<span class="sublabel">Account number - IBAN</span><br>
		<span class="value"><?php echo $debtor_iban; ?></span>
	</td>
</tr>
<tr>
	<td colspan="2" style="padding-top: 14px; padding-bottom: 14px;">
		<table style="width: 100%;">
		<tr>
			<td width="20%"><span class="label">Tipo de pago:</span><br><span class="sublabel">Type of payment</span></td>
			<td width="30%" style="font-size: 13px;">[ &nbsp;&nbsp; ] Recurrente<br><span class="sublabel">Recurrent payment</span></td>
			<td width="10%" class="sublabel">o / or</td>
			<td width="40%" style="font-size: 13px;">[ &nbsp;&nbsp; ] Único<br><span class="sublabel">One-off payment</span></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="50%" style="height: 85px;">
		<span class="label">Fecha - Localidad</span><br>
		<span class="sublabel">Date - location in which you are signing</span>
	</td>
	<td width="50%" style="height: 85px;">
		<span class="label">Firma del deudor</span><br>
		<span class="sublabel">Signature of the debtor</span>
	</td>
</tr>
</table>

<div class="legal-center">
TODOS LOS CAMPOS HAN DE SER CUMPLIMENTADOS OBLIGATORIAMENTE.<br>
UNA VEZ FIRMADA ESTA ORDEN DE DOMICILIACIÓN DEBE SER ENVIADA AL ACREEDOR PARA SU CUSTODIA<br>
LA ENTIDAD DEUDORA REQUIERE AUTORIZACIÓN DE ÉSTE PREVIA AL CARGO EN CUENTA DE LOS ADEUDOS DIRECTOS B2B<br>
EL DEUDOR PODRÁ GESTIONAR DICHA AUTORIZACIÓN CON LOS MEDIOS QUE SU ENTIDAD PONGA A SU DISPOSICIÓN<br>
<i>ALL FIELDS ARE MANDATORY. ONCE THIS MANDATE HAS BEEN SIGNED IT MUST BE SENT TO THE CREDITOR FOR STORAGE.<br>
NEVERTHELESS, THE DEBTOR'S BANK REQUIRES THE DEBTOR'S AUTHORISATION BEFORE DEBITING B2B DIRECT DEBITS IN THE ACCOUNT.<br>
THE DEBTOR WILL BE ABLE TO MANAGE THE MENTIONED AUTHORISATION THROUGH THE MEANS PROVIDED BY HIS BANK.</i>
</div>
</body>
</html>
