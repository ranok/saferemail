var privKey, pubKey;
var toPubKey;
openpgp.init();

function loadKeyFile()
{   
    var fr = new FileReader(), file, input = document.getElementById("keyfile");

    if (!input.files)
	{
	    alert("No files!");
	    return false;
	}
    if (!input.files[0])
	{
	    alert("No file selected!");
	    return false;
	}
    file = input.files[0];
    fr.onload = function(e) {
	// FIXME
	alert("Key loaded");
	localStorage.setItem("key", e.target.result);
    }
    fr.readAsText(file);
    
    return false;
}

function generateKey(name, email, bits)
{
    var keys;
    if (window.crypto.getRandomValues)
	{
	    keys = openpgp.generate_key_pair(1, bits, name + " <" + email + ">");
	    // document.getElementById("privatekey").textContent = keys["privateKeyArmored"];
	    // document.getElementById("publickey").textContent = keys["publicKeyArmored"];
	    openpgp.keyring.importPrivateKey(keys["privateKeyArmored"]);
	    openpgp.keyring.importPublicKey(keys["publicKeyArmored"]);
	    openpgp.keyring.store();
	    //	    document.location = 'data:Application/octet-stream,' + encodeURIComponent(keys["privateKeyArmored"]);
	    return true;
	}
    else
	{
	    alert("Sorry, your browser does not support key generation!");
	}
    return false;
}

function rsaDecrypt(privkey, message)
{
    var msg = openpgp.read_message(message)[0];
    var pk = openpgp.read_privateKey(privkey)[0];
    var keymat = {key: pk, keymaterial: pk.privateKeyPacket};
    return msg.decrypt(keymat, msg.sessionKeys[0]);
}

function rsaEncrypt(pubkey, message, sign)
{
    return openpgp.write_encrypted_message(pubkey, message);
}

function symmetricDecrypt(key, message)
{
    return openpgp_crypto_symmetricDecrypt(10, key, message, true);
}

function symmetricEncrypt(key, message)
{
    return openpgp_crypto_symmetricEncrypt(openpgp_crypto_getPrefixRandom(10), 10, key, message, true);
}

function determineEncStatus()
{
    var email = document.getElementById("toemail").value;
    $.ajax({
	    url: "api_handler.php",
		data: {method : "get_public_key", email : email}
	}).done(function (data) {
		if (data != '')
		    {
			toPubKey = openpgp.read_publicKey(data);
			document.getElementById("emailstatus").textContent = "Public key found, message will be secured";
		    }
		else
		    {
			document.getElementById("emailstatus").textContent = "No public key found, message will NOT be secured";
		    }
	    });
    
}

function showMessage(id)
{
    $.ajax({
	    url: "api_handler.php",
		data: {method: "get_message", id: id}
	}).done(function (data) {
		
		document.getElementById("messagebox").textContent = rsaDecrypt(openpgp.keyring.exportPrivateKey(0).armored, data);
	    });
}

function sendMessage()
{
    var message, email, subject, encmessage, post = "false";
    message = document.getElementById("message").value;
    email = document.getElementById("toemail").value;
    subject = document.getElementById("subject").value;

    if (toPubKey)
	{
	    encmessage = rsaEncrypt(toPubKey, message, false);
	    post = "true";
	}
    else
	{
	    encmessage = message;
	}
    
    $.ajax({
	    url: "api_handler.php?method=send",
		type: "POST",
		data: {email: email, subject: subject, message: encmessage, post: post}
	}).done(function (data) {
		document.getElementById("composeform").reset();
	    });
}

function logOff()
{
    localStorage.removeItem("privatekeys");
    localStorage.removeItem("publickeys");
    $.ajax({
	    url: "api_handler.php",
		data: {method : "logoff"}
	}).done(function (data) {
		document.location.reload();
	    });
}

function validateSmailEmail()
{
    var email = $("#createemail").val() + '@' + emaildomain;
    document.getElementById("createemailcheck").textContent = "";
    $.ajax({
	    url: "api_handler.php",
		data: {method : "user_exists", email : email}
	}).done(function (data) {
		if (data == 'false')
		    document.getElementById("createemailcheck").textContent = email + " is available!";
	    });
    
}

function createUser()
{
    var email = $("#createemail").val() + '@' + emaildomain;
    var name = $("#createname").val();
    if (generateKey(name, email, 1024))
	{
	    var pubkey = openpgp.keyring.getPublicKeyForAddress(email)[0].armored;
	    var privkey = openpgp.keyring.getPrivateKeyForAddress(email)[0].armored;
	    var encprivkey = escape(symmetricEncrypt($("#createpassword").val(), privkey));
	    
	    postToUrl('dologin.php', {method: 'create_user', pubkey: pubkey, email: email, name: name, encprivkey: encprivkey});
	}
}

function postToUrl(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
	if(params.hasOwnProperty(key)) {
	    var hiddenField = document.createElement("input");
	    hiddenField.setAttribute("type", "hidden");
	    hiddenField.setAttribute("name", key);
	    hiddenField.setAttribute("value", params[key]);

	    form.appendChild(hiddenField);
	}
    }

    document.body.appendChild(form);
    form.submit();
}

function login()
{
    var email = document.getElementById("loginemail").value;
    if (email.indexOf("@") == -1)
	email = email + '@' + emaildomain;
    $.ajax({
	    url: "api_handler.php",
		data: {method : "get_enc_privkey", email : email}
	}).done(function (data) {
		if (data != '')
		    {
			var privkey = '';
			try
			    {
				privkey = symmetricDecrypt($("#loginpassword").val(), unescape(data));
			    }
			catch(err)
			    {
				// Do nothing
			    }
			if (privkey.match('-----BEGIN PGP'))
			    {
				openpgp.keyring.importPrivateKey(privkey);
				openpgp.keyring.store();
				$.ajax({
					url: "api_handler.php",
					    data: {method : "get_enc_nonce", email : email}
				    }).done(function (data) {
					    if (data != '')
						{
						    var nonce = rsaDecrypt(openpgp.keyring.exportPrivateKey(0).armored, data);
						    postToUrl('dologin.php', {method: 'login', nonce: nonce});
						}
					});
			    }
			else
			    {
				alert("Sorry, incorrect username/password");
			    }
		    }
		else
		    {
			alert("Sorry, incorrect username/password");
		    }
	    });    
}

function loadFileDiv(id, file) {
	$(id).load(file);
}

function hideShowByID(hide, show) {
	hideDiv(hide);
	showDiv(show);
}

function hideDiv(devid) { 
	if (document.getElementById) { // DOM3 = IE5, NS6 
		document.getElementById(devid).style.display = 'none'; 
	}  
}

function showDiv(devid) { 
	if (document.getElementById) { // DOM3 = IE5, NS6 
		document.getElementById(devid).style.display = 'block'; 
	} 
} 
