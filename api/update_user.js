export async function updateUser(rawid, name, email, role, status) {
    const id = parseInt(rawid.replace('#', ''), 10);
    
    const userData = {
        id: id,
        name: name,
        email: email,
        role: role,
        status: status
    };

    console.log(userData);

    try {
        const response = await fetch('api/update_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
                console.log('API result: ' + result);
        return result;

    } catch (error) {
        console.error('Fejl i updateUser API-kald:', error);
        return false;
    }
}