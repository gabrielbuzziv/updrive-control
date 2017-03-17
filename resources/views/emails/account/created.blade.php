<body>
    <h1>Welcome to the SaaS!</h1>

    <p>Your SaaS instance has been installed. You can access by the following URL: http://{{ $account->slug }}.saas.app</p>
    <p>To start configuring your app, you will need to log in using the credentials below.</p>
    <ul>
        <li>E-mail: {{ $account->email }}</li>
        <li>Password: {{ $password }}</li>
    </ul>

</body>