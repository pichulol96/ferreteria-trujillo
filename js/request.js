async function request (url, data){
    try {
        const response = await fetch(url, {
        method: "POST", // or 'PUT'
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Error:", error);
        //return error;
    }
}
async function formDataRequest (url, data){
    try {
        const response = await fetch(url, {
        method: "POST", // or 'PUT'
        body: data,
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Error:", error);
        //return error;
    }
}

async function GetRequest (url){
    try {
        const response = await fetch(url);
        const result = await response.json();
        return result;
    } catch (error) {
        console.error("Error:", error);
        //return
    }
}