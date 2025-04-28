const functions = require('firebase-functions');
const axios = require('axios');

exports.sendSMS = functions.https.onRequest(async (req, res) => {
  const { phone, code } = req.body;
  const apiKey = functions.config().kavenegar.api_key;
  const template = functions.config().kavenegar.template;

  // Input validation
  if (!phone || !code) {
    console.error('Phone number and code are required.');
    return res.status(400).send('Phone number and code are required.');
  }

  // Phone number validation (Iranian format)
  const phoneRegex = /^09\d{9}$/;
  if (!phoneRegex.test(phone)) {
    console.error('Invalid phone number format. Must be an Iranian phone number starting with 09 and 11 digits.');
    return res.status(400).send('Invalid phone number format.');
  }

  // Code validation (6 digits)
  const codeRegex = /^\d{6}$/;
  if (!codeRegex.test(code)) {
    console.error('Invalid code format. Must be a 6-digit number.');
    return res.status(400).send('Invalid code format.');
  }

  try {
    const response = await axios.get(`https://api.kavenegar.com/v1/${apiKey}/verify/lookup.json`, {
      params: {
        receptor: phone,
        token: code,
        template
      }
    });

    console.log('Kavenegar API Response:', response.data);
    res.status(200).send('SMS sent successfully.');
  } catch (error) {
    console.error('Error sending SMS:', error);
    res.status(500).send('Error sending SMS: ' + error.message);
  }
});
