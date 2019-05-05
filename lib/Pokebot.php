<?php

namespace Pokebot;

class Pokebot
{
	public function addLobbies(&$gyms) {
		global $pokebotDb;

		$query = "SELECT rl.gym_id, rl.user_id, rlm.count as count
					FROM lobby_members rl
					JOIN (
						SELECT SUM(count) AS count, gym_id
					    FROM lobby_members
					    GROUP BY gym_id
					) AS rlm ON rlm.gym_id = rl.gym_id";

		$lobbies = $pokebotDb->query($query)->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($gyms as &$gym) {
			$key = array_search($gym["gym_id"], array_column($lobbies, 'gym_id'));
			if (is_int($key)) {
				$gym["count"] = $lobbies[$key]["count"];
				$gym["gym_id"] = $lobbies[$key]["gym_id"];
			} else {
				$gym["count"] = 0;
				$gym["gym_id"] = null;
			}
		}
	}

	public function getLobbyInfo($gymId) {
		global $pokebotDb;

		$query = "SELECT time, SUM(count) AS count
					FROM lobby_members
					WHERE gym_id = ".$gymId."
					GROUP BY time";

		return $novabotDb->query($query)->fetchAll(\PDO::FETCH_ASSOC);
	}
}
