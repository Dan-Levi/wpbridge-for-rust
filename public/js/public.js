(async function($)
{
    "use strict"
    await UpdateHeaderServerStatusElem($);
    await UpdatePlayerCountElem($);
    await UpdatePlayerListElem($);
    
})(jQuery)

async function UpdateHeaderServerStatusElem($)
{
    const headerServerStatusElem = $("#header-rust-server-api-server-status");
    if(headerServerStatusElem.length > 0)
    {
        let serverId = headerServerStatusElem.data('id');
        let data = await RustServerAPIFetchServerInfo(serverId);
        if(data)
        {
            if(data.status == "Offline")
            {
                headerServerStatusElem
                .text('Status: ' + data.status + '.');
            } else
            {
                headerServerStatusElem
                .text('Status: ' + data.status + '. ' + 'Last restart: ' + data.uptime + ' ago.');
            }
        } else{
            headerServerStatusElem
            .text('Server info unavailable right now.');
        }
    }
}

async function UpdatePlayerCountElem($)
{
    const playerCountElem = $(".rust-server-api-player-count");
    
    if(playerCountElem.length > 0)
    {
        for (let i = 0; i < playerCountElem.length; i++) {
            const currentPlayerCountElem = playerCountElem[i];
            let serverId = $(currentPlayerCountElem).data('id');
            let data = await RustServerAPIFetchPlayerInfo(serverId);
        
            if(data.length > 0)
            {
                $(currentPlayerCountElem).text(`Active Players | ${data.length} online at the moment`);
            } else
            {
                $(currentPlayerCountElem).text(`No Players online at the moment`);
            }
        }
    }
}

async function UpdatePlayerListElem($)
{
    const playerListElem = $(".rust-server-api-player-list");
    
    if(playerListElem.length > 0)
    {
        let serverId = playerListElem.data('id');
        let data = await RustServerAPIFetchPlayerInfo(serverId);
        if(data.length > 0)
        {
            const tableElem = $("<table></table>");
            const tableBody = $("<tbody></tbody>");
            tableBody.append(`<tr><td><i>Player</i></td><td><i>Playtime</i></td></tr>`);
            data.forEach(player => {
                tableBody.append(`<tr><td>${player.name}</td><td>${player.play_time_human}</td></tr>`);
            });
            tableElem.append(tableBody);
            playerListElem.html(tableElem);
        } else
        {
            playerListElem.text(``);
        }
    }
}

async function RustServerAPIFetchPlayerInfo(serverId)
{
    
    const json = await RustServerAPIFetch(serverId,"players");
    if(!json) console.error(`Unable to fetch players from api.rust-servers.info for server: ${serverId}`);
    return json;
}

async function RustServerAPIFetchServerInfo(serverId)
{
    const json = await RustServerAPIFetch(serverId,"status");
    if(!json) console.error(`Unable to fetch status from api.rust-servers.info for server: ${serverId}`);
    return json;
}

async function RustServerAPIFetch(serverId, type)
{

    const serverStatusEndpoint = `https://api.rust-servers.info/${type}/${serverId}`;
    
    try {
        let response = await fetch(serverStatusEndpoint);
        return await response.json();
    } catch (err) 
    {
        
        return false;
    } 
}