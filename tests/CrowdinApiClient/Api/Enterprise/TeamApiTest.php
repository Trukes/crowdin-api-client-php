<?php

namespace CrowdinApiClient\Api\Enterprise;

use CrowdinApiClient\Model\Enterprise\Team;
use CrowdinApiClient\ModelCollection;
use CrowdinApiClient\Tests\Api\Enterprise\AbstractTestApi;

class TeamApiTest extends AbstractTestApi
{
    public function testList()
    {
        $this->mockRequest([
            'path' => '/teams',
            'method' => 'get',
            'response' => '{
                  "data": [
                    {
                      "data": {
                        "id": 2,
                        "name": "Team 1",
                        "totalMembers": 8,
                        "createdAt": "2019-09-23T09:04:29+00:00",
                        "updatedAt": "2019-09-23T09:04:29+00:00"
                      }
                    }
                  ],
                  "pagination": [
                    {
                      "offset": 0,
                      "limit": 0
                    }
                  ]
                }'
        ]);

        $teams = $this->crowdin->team->list();
        $this->assertInstanceOf(ModelCollection::class, $teams);
        $this->assertCount(1, $teams);
        $this->assertInstanceOf(Team::class, $teams[0]);
        $this->assertEquals(2, $teams[0]->getId());
    }

    public function testCreate()
    {
        $params = [
            'name' => 'Team 1',
        ];

        $this->mockRequest([
            'path' => '/teams',
            'method' => 'post',
            'body' => $params,
            'response' => '{
                  "data": {
                    "id": 2,
                    "name": "Team 1",
                    "totalMembers": 8,
                    "createdAt": "2019-09-23T09:04:29+00:00",
                    "updatedAt": "2019-09-23T09:04:29+00:00"
                  }
                }'

        ]);

        $team = $this->crowdin->team->create($params);
        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(2, $team->getId());
    }

    public function testGetAndUpdate()
    {
        $this->mockRequestGet('/teams/2', '{
                  "data": {
                    "id": 2,
                    "name": "Team 1",
                    "totalMembers": 8,
                    "createdAt": "2019-09-23T09:04:29+00:00",
                    "updatedAt": "2019-09-23T09:04:29+00:00"
                  }
                }');

        $team = $this->crowdin->team->get(2);
        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(2, $team->getId());

        $this->mockRequestPath('/teams/2', '{
                  "data": {
                    "id": 2,
                    "name": "test edit",
                    "totalMembers": 8,
                    "createdAt": "2019-09-23T09:04:29+00:00",
                    "updatedAt": "2019-09-23T09:04:29+00:00"
                  }
                }');

        $team->setName('test edit');
        $team = $this->crowdin->team->update($team);
        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(2, $team->getId());
        $this->assertEquals('test edit', $team->getName());
    }

    public function testDelete()
    {
        $this->mockRequestDelete('/teams/2');
        $this->crowdin->team->delete(2);
    }
}
