export async function updateSettings(table, id, name, desc, color) {
   
    const data = {
        table: table,
        id: id,
        name: name,
        description: desc,
        color: color
    };

    try {
        const response = await fetch('api/update_settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();

        return result;

    } catch (error) {
        console.error('Fejl i updateSettings API-kald:', error.message);
        return false;
    }
}

export async function updateSettingTypes(updateData) {

    try {
        const response = await fetch('api/update_settingtypes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updateData)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
        return result;

    } catch (error) {
        console.error('Fejl i updateSettings API-kald:', error.message);
        return false;
    }
}