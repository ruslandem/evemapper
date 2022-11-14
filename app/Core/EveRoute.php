<?php

namespace App\Core;

use Fisharebest\Algorithm\Dijkstra;
use Illuminate\Database\ConnectionInterface;

class EveRoute
{
    protected array $systemJumps = [];
    protected array $systemNames = [];


    public function __construct(ConnectionInterface $db)
    {
        $data = $db->table('mapSolarSystemJumps')->get();
        foreach ($data as $item) {
            $this->systemJumps[intval($item->fromSolarSystemID)][intval($item->toSolarSystemID)] = 1;
        }

        $data = $db->table('mapSolarSystems')->get();
        foreach ($data as $item) {
            $this->systemNames[intval($item->solarSystemID)] = $item->solarSystemName;
        }
    }

    public function getRoute(string $fromSolarSystemName, string $toSolarSystemName)
    {
        $fromSolarSystemId = array_search($fromSolarSystemName, $this->systemNames, true);
        $toSolarSystemId = array_search($toSolarSystemName, $this->systemNames, true);

        if (!$fromSolarSystemId || !$toSolarSystemId) {
            throw new \Exception('origin or target system not found');
        }

        $algorithm = new Dijkstra($this->systemJumps);
        $route = $algorithm->shortestPaths($fromSolarSystemId, $toSolarSystemId);

        if (isset($route[0])) {
            return Utils::mapArray($route[0], $this->systemNames);
        }

        return [];
    }

    // Query get XYZ-positions between gates

    // SELECT invItems.itemID as originID, invItems.locationID AS originLocationID, invNames.itemName, mapJumps.destinationID, destinationItems.locationID AS destinationLocationID,
    // originPositions.x as originX, originPositions.y as originY, originPositions.z as originZ,
    // destinationPositions.x as destinationX, destinationPositions.y as destinationY, destinationPositions.z as destinationZ
    // FROM invItems 
    // LEFT JOIN invNames ON invNames.itemID = invItems.itemID
    // LEFT JOIN invTypes ON invTypes.typeID = invItems.typeID
    // LEFT JOIN mapJumps ON mapJumps.stargateID = invItems.itemID
    // LEFT JOIN invItems AS destinationItems ON destinationItems.itemID = mapJumps.destinationID
    // LEFT JOIN invPositions AS originPositions ON originPositions.itemID = invNames.itemID
    // LEFT JOIN invPositions AS destinationPositions ON destinationPositions.itemID = mapJumps.destinationID
    // WHERE invItems.locationID = 30000140 AND invTypes.groupID = 10

}
