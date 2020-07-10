const ewelink = require('ewelink-api');

/* first request: get access token and api key */
(async () => {

  const connection = new ewelink({
    email: 'murtaza.hanif@gmail.com',
    password: 'byby4466',
  });

  const login = await connection.login();

  const accessToken = login.at;
  const apiKey = login.user.apikey
 // console.log(accessToken)
  /* second request: use access token to request devices */
  const newConnection = new ewelink({ at: accessToken });
  const devices = await newConnection.getDevices();
  console.log(devices);
  // /* third request: use access token to request specific device info */
  // const thirdConnection = new ewelink({ at: accessToken });
  // const device = await thirdConnection.getDevice('<your device id>');
  // console.log(device);
  // /* fourth request: use access token and api key to toggle specific device info */
  // const anotherNewConnection = new ewelink({ at: accessToken, apiKey: apiKey });
  // await anotherNewConnection.toggleDevice('<your device id>');

})();