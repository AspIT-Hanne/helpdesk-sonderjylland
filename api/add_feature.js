export async function addFeature(version, content) {
    const featureData = {
        version: version,
        content: content
    };

    try {
        const response = await fetch('api/add_feature.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(featureData)
        });
                // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
        return result;

    } catch (error) {
        console.error('Fejl i addFeature API-kald:', error);
        // Returner false (eller kast fejlen videre), så din handleCreateUserSubmit ved det gik galt
        return false;
    }
}