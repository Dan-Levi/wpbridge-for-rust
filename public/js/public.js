(async function($)
{
    "use strict"
    
    const headerServerStatusElem = $("#header-server-status");
    if(headerServerStatusElem.length > 0)
    {
        let serverId = headerServerStatusElem.data('id');
        let data = await FetchServerInfo(serverId);
        if(data)
        {
            headerServerStatusElem
            .text('Status: ' + data.status + '. ' + 'Last restart: ' + data.uptime + ' ago.');
        } else{
            headerServerStatusElem
            .text('Server info unavailable right now.');
        }
    }
    
})(jQuery)

async function FetchServerInfo(serverId)
{
    const serverStatusEndpoint = `https://api.rust-servers.info/status/${serverId}`;
    
    try {
        let response = await fetch(serverStatusEndpoint);
        return await response.json();
    } catch (err) 
    {
        console.error("Unable to fetch server status from ");
        return false;
    }    
}