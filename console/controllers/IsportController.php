<?php

namespace console\controllers;


use cms\models\FbFifaRank;
use cms\models\FbPlayer;
use cms\models\FbPlayerstat;
use wap\models\FbTopscorer;
use common\components\CFunction;
use common\models\FbOddOtherBase;
use common\models\FbStandingsBase;
use common\models\FbStatsBase;
use common\components\Isport;
use common\components\KLogger;
use common\components\Utility;
use common\models\FbCountryBase;
use common\models\FbEventBase;
use common\models\FbLineupBase;
use common\models\FbOddsBase;
use common\models\LeagueBase;
use common\models\MatchBase;
use common\models\TeamBase;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class IsportController extends Controller
{
    public $dirImage = 'E:\xampp_x\htdocs\keobongda\cms\www\uploads/';

    public function actionLeague()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_league_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start league ========');
        $leagues = Isport::instance()->getLeague();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($leagues['data'])) {
                $count = 1;
                foreach ($leagues['data'] as $league) {
                    //if ($league['currentSeason'] != '2020' && $league['currentSeason'] != '2020-2021') continue;

                    //$logger->LogInfo($count . ' ----------> leagueId: ' . $league['leagueId']);
                    //$logger->LogInfo($count . ' ----------> currentSeason: ' . $league['currentSeason']);

                    $model = LeagueBase::findOne($league['leagueId']);
                    if (empty($model)) {
                        $model = new LeagueBase();
                        $model->created_time = date('Y-m-d H:i:s');
                        $model->league_id = $league['leagueId'];
                        /*if (!empty($league['logo'])) {
                            $urlImage = strtok($league['logo'], '?');
                            $ext = pathinfo($urlImage, PATHINFO_EXTENSION);
                            if (!empty($ext)) {
                                $filename = md5($urlImage) . '.' . $ext;
                                if (!file_exists($this->dirImage . $filename)) {
                                    //$logger->LogInfo('------> doanload logo : ' . $league['logo']);
                                    Utility::saveImageFromUrl($league['logo'], $this->dirImage . $filename);
                                }
                                $model->logo = $filename;
                            }
                        }
                        if (!empty($league['countryLogo'])) {
                            $urlImage = strtok($league['countryLogo'], '?');
                            $ext = pathinfo($urlImage, PATHINFO_EXTENSION);
                            if (!empty($ext)) {
                                $filename = 'league_matches/' . md5($urlImage) . '.' . $ext;
                                if (!file_exists($this->dirImage . $filename)) {
                                    //$logger->LogInfo('------> doanload countryLogo : ' . $league['countryLogo']);
                                    Utility::saveImageFromUrl($league['countryLogo'], $this->dirImage . $filename);
                                }
                                $model->countryLogo = $filename;
                            }
                        }*/

                        $model->logo = $league['logo'];
                        $model->countryLogo = $league['countryLogo'];
                        $model->name = $league['name'];
                        $model->short_name = $league['shortName'];
                        $model->sort_order = 1000;
                        $model->slug = CFunction::unsignString($league['name']);
                    }
                    $model->color = $league['color'];
                    $model->type = $league['type'];

                    $model->sub_league_name = $league['subLeagueName'];

                    $model->countryId = $league['countryId'];
                    $model->country = $league['country'];

                    $model->totalRound = $league['totalRound'];
                    $model->currentRound = $league['currentRound'];
                    $model->currentSeason = $league['currentSeason'];
                    $model->areaId = $league['areaId'];

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }

    }


    public function actionTeamLeagueHot()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $leagues = LeagueBase::findAll(['isHot' => 1]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                //$logger->LogInfo(' ======> league_id: ' . $league->league_id);
                $teams = Isport::instance()->getTeams($league->league_id);
                try {
                    if (!empty($teams['data'])) {
                        foreach ($teams['data'] as $team) {
                            //$logger->LogInfo(' ++ teamId: ' . $team['teamId']);
                            $model = TeamBase::findOne($team['teamId']);
                            if (empty($model)) {
                                $model = new TeamBase();
                                $model->created_time = date('Y-m-d H:i:s');
                                $model->leagueId = $team['leagueId'];
                                $model->teamId = $team['teamId'];
                                $model->logo = $team['logo'];
                            }
                            /*if (!empty($team['logo'])) {
                                $urlImage = strtok($team['logo'], '?');
                                $ext = pathinfo($urlImage, PATHINFO_EXTENSION);
                                if (!empty($ext)) {
                                    $filename = 'teams/' . md5($urlImage) . '.' . $ext;
                                    $dest = $this->dirImage . $filename;
                                    if (!file_exists($dest)) {
                                        //$logger->LogInfo('doanload logo : ' . $team['logo']);
                                        Utility::saveImageFromUrl($team['logo'], $dest);
                                    }
                                    $model->logo = $filename;
                                }
                            }*/

                            $model->name = $team['name'];
                            $model->foundingDate = $team['foundingDate'];
                            $model->address = $team['address'];
                            $model->area = $team['area'];
                            $model->venue = $team['venue'];
                            $model->capacity = $team['capacity'];
                            $model->coach = $team['coach'];
                            $model->website = $team['website'];
                            $model->created_time = date('Y-m-d H:i:s');

                            $model->save(false);
                        }
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                }
            }
        }
    }


    public function actionTeamAll()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog('=================== start team all');
        //$leagues = LeagueBase::findAll(['status' => 1]);
        //if (!empty($leagues)) {
        $teams = Isport::instance()->getTeams();
        try {
            if (!empty($teams['data'])) {
                $count = 0;
                foreach ($teams['data'] as $team) {
                    //$logger->LogInfo(' ++ teamId: ' . $team['teamId']);
                    $model = TeamBase::findOne($team['teamId']);
                    if (empty($model)) {
                        $model = new TeamBase();
                        $model->created_time = date('Y-m-d H:i:s');
                        $model->teamId = $team['teamId'];
                        $model->logo = $team['logo'];
                    }
                    /*if (!empty($team['logo'])) {
                        $urlImage = strtok($team['logo'], '?');
                        $ext = pathinfo($urlImage, PATHINFO_EXTENSION);
                        if (!empty($ext)) {
                            $filename = 'teams/' . md5($urlImage) . '.' . $ext;
                            $dest = $this->dirImage . $filename;
                            if (!file_exists($dest)) {
                                //$logger->LogInfo('doanload logo : ' . $team['logo']);
                                Utility::saveImageFromUrl($team['logo'], $dest);
                            }
                            $model->logo = $filename;
                        }
                    }*/
                    $model->leagueId = $team['leagueId'];
                    $model->name = $team['name'];
                    $model->foundingDate = $team['foundingDate'];
                    $model->address = $team['address'];
                    $model->area = $team['area'];
                    $model->venue = $team['venue'];
                    $model->capacity = $team['capacity'];
                    $model->coach = $team['coach'];
                    $model->website = $team['website'];
                    $model->created_time = date('Y-m-d H:i:s');

                    $model->save(false);
                    $count++;
                }
                $this->echoLog('Total: ' . $count);
            } else {
                $this->echoLog('Error data: ' . json_encode($teams));
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
            $this->echoLog('Exception: ' . $exception->getMessage());
        }

        //}
        $this->echoLog(' ################### END');
    }


    /*public function actionMatches($date = null)
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_match_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $matches = Isport::instance()->getMatches($date);
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($matches['data'])) {
                $count = 1;
                foreach ($matches['data'] as $match) {

                    $model = MatchBase::findOne($match['matchId']);
                    if (empty($model)) {
                        $model = new MatchBase();
                        $model->matchId = $match['matchId'];
                        $model->leagueId = $match['leagueId'];
                        $model->leagueType = $match['leagueType'];
                        $model->created_time = date('Y-m-d H:i:s');
                        $model->homeName = $match['homeName'];
                        $model->awayName = $match['awayName'];

                        $model->homeId = $match['homeId'];

                        $model->awayId = $match['teamId'];

                    } elseif ($model->status == MatchBase::STATUS_FINISHED && $model->status == $match['status']) {
                        continue;
                    }
                    //$logger->LogInfo($count . ' -#########b MATCHID: ' . $match['matchId']);

                    $model->extraExplain = json_encode($match['extraExplain']);
                    //$model->leagueShortName = $match['leagueShortName'];
                    $model->leagueName = $match['leagueName'];
                    $model->matchTime = $match['matchTime'];
                    $model->status = $match['status'];
                    $model->homeScore = $match['homeScore'];
                    $model->awayScore = $match['awayScore'];
                    $model->explain = $match['explain'];
                    $model->neutral = $match['neutral'];
                    $model->updated_time = date('Y-m-d H:i:s');

                    $model->save(false);
                    $count++;
                }
            }else {
                //$logger->LogInfo('Response Error: ' . json_encode($matches));
                $this->echoLog('Response Error: ' . json_encode($matches));
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }

    }*/

    public function actionLivescore()
    {
        ////$logger = new KLogger('logs' . DS.'isports' .DS. 'get_live_isport_data_' . date('Ymd'), KLogger::INFO);
        ////$logger->LogInfo('======== start live match ========');
        $date = date('Y-m-d');
        echo PHP_EOL . 'Start : ' . date('Y-m-d H:i:s');
        $matches = Isport::instance()->getLivescore();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        //$timeSleep = 400;
        // while (true) {
        // echo PHP_EOL . json_encode($matches);
        try {
            if (!empty($matches['data'])) {
                $count = 1;
                foreach ($matches['data'] as $match) {
                    $model = MatchBase::findOne($match['matchId']);
                    if (empty($model) || ($model->status <= 0 && $model->status == $match['status'])) {
                        continue;
                    }
                    ////$logger->LogInfo($count . ' -#########b MATCHID: ' . $match['matchId']);
                    ////$logger->LogInfo($count . ' data : ' . json_encode($match));
                    echo PHP_EOL . $count . ' -#########b MATCHID: ' . $match['matchId'];
                    $model->matchTime = $match['matchTime'];
                    $model->extraExplain = json_encode($match['extraExplain']);
                    $model->status = $match['status'];
                    //$model->startTime = isset($match['startTime'])?$match['startTime']:0;
                    $model->halfStartTime = $match['halfStartTime'];
                    $model->homeScore = $match['homeScore'];
                    $model->awayScore = $match['awayScore'];
                    $model->explain = $match['explain'];
                    $model->homeHalfScore = $match['homeHalfScore'];
                    $model->awayHalfScore = $match['awayHalfScore'];
                    $model->homeRed = $match['homeRed'];
                    $model->awayRed = $match['awayRed'];
                    $model->homeYellow = $match['homeYellow'];
                    $model->awayYellow = $match['awayYellow'];
                    $model->homeCorner = $match['homeCorner'];
                    $model->awayCorner = $match['awayCorner'];
                    $model->hasLineup = (isset($match['hasLineup']) && $match['hasLineup'] === true) ? 1 : 0;

                    $model->updated_time = date('Y-m-d H:i:s');

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            ////$logger->LogInfo('Exception: ' . $exception->getMessage());
        }
        echo PHP_EOL . 'END : ' . date('Y-m-d H:i:s');
        //sleep($timeSleep);
        //}
    }

    public function actionLivescoreChange()
    {
        ////$logger = new KLogger('logs' . DS.'isports' .DS.'get_live_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start live match ========');
        $date = date('Y-m-d');
        $matches = Isport::instance()->getLiveChange();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($matches['data'])) {
                $count = 1;
                foreach ($matches['data'] as $match) {
                    $model = MatchBase::findOne($match['matchId']);
                    if (empty($model)) {
                        continue;
                    }
                    //$logger->LogInfo($count . ' -#########b MATCHID: ' . $match['matchId']);
                    //$logger->LogInfo($count . ' data : ' . json_encode($match));

                    $model->extraExplain = json_encode($match['extraExplain']);
                    $model->status = $match['status'];
                    $model->startTime = isset($match['startTime']) ? $match['startTime'] : 0;
                    //$model->matchTime = $match['matchTime'];
                    //$model->halfStartTime = isset($match['halfStartTime'])?$match['halfStartTime']:0;
                    $model->homeScore = $match['homeScore'];
                    $model->awayScore = $match['awayScore'];
                    $model->explain = $match['explain'];
                    $model->homeHalfScore = isset($match['homeHalfScore']) ? $match['homeHalfScore'] : 0;
                    $model->awayHalfScore = isset($match['awayHalfScore']) ? $match['awayHalfScore'] : 0;
                    $model->homeRed = isset($match['homeRed']) ? $match['homeRed'] : 0;
                    $model->awayRed = isset($match['awayRed']) ? $match['awayRed'] : 0;
                    $model->homeYellow = isset($match['homeYellow']) ? $match['homeYellow'] : 0;
                    $model->awayYellow = isset($match['awayYellow']) ? $match['awayYellow'] : 0;
                    $model->homeCorner = isset($match['homeCorner']) ? $match['homeCorner'] : 0;
                    $model->awayCorner = isset($match['awayCorner']) ? $match['awayCorner'] : 0;
                    $model->hasLineup = (isset($match['hasLineup']) && $match['hasLineup'] === true) ? 1 : 0;

                    $model->updated_time = date('Y-m-d H:i:s');

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }

    }

    public function actionEvents($date = null)
    {
        echo PHP_EOL . 'Start : ' . date('Y-m-d H:i:s');
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_events_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');
        if (empty($date)) {
            if (empty($date)) {
                $time = time();
                $hour = (int)date('H');
                if ($hour >= 0 && $hour < 7) {
                    $time = strtotime('-1 day');
                }
                $date = date('Y-m-d', $time);
            }
        }
        $events = Isport::instance()->getEvents($date);
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($events['data'])) {
                $count = 1;
                foreach ($events['data'] as $event) {
                    if (empty($event['events'])) continue;
                    $model = FbEventBase::findOne($event['matchId']);
                    if (empty($model)) {
                        $model = new FbEventBase();
                        $model->matchId = $event['matchId'];
                        $model->created_time = date('Y-m-d H:i:s');
                    }
                    $this->echoLog('MatchId : ' . $event['matchId']);
                    $this->echoLog('------> events : ' . json_encode($event['events']));
                    $model->updated_time = date('Y-m-d H:i:s');
                    $model->events = json_encode($event['events']);

                    $model->save(false);
                    $count++;
                }
            }else{
                $this->echoLog('NO DATA : ' . json_encode($events));
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }
        echo PHP_EOL . 'END : ' . date('Y-m-d H:i:s');
    }

    public function actionLineup()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_events_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');
        echo PHP_EOL . 'Start : ' . date('Y-m-d H:i:s');
        $events = Isport::instance()->getLineup();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($events['data'])) {
                $count = 1;
                foreach ($events['data'] as $event) {
                    if (empty($event['homeLineup'])) continue;
                    $model = FbLineupBase::findOne($event['matchId']);
                    if (empty($model)) {
                        $model = new FbLineupBase();
                        $model->matchId = $event['matchId'];
                    }

                    $model->update_time = date('Y-m-d H:i:s');
                    $model->homeFormation = $event['homeFormation'];
                    $model->awayFormation = $event['awayFormation'];
                    $model->homeLineup = json_encode($event['homeLineup']);
                    $model->awayLineup = json_encode($event['awayLineup']);
                    $model->homeBackup = json_encode($event['homeBackup']);
                    $model->awayBackup = json_encode($event['awayBackup']);

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }
        echo PHP_EOL . 'END : ' . date('Y-m-d H:i:s');
    }

    public function actionMatchLive($date = null)
    {
        set_time_limit(0);
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_match_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');
        $this->echoLog('==================Start : Match Live ===============');
        if (empty($date)) {
            $date = Utility::getCurrentDate();
        }
        $this->echoLog('Date : ' . $date);
        $matches = Isport::instance()->getMatchLive($date);
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($matches['data'])) {
                $count = 0;
                foreach ($matches['data'] as $match) {

                    $model = MatchBase::findOne($match['matchId']);
                    if (empty($model)) {
                        $model = new MatchBase();
                        $model->matchId = $match['matchId'];
                        $model->created_time = date('Y-m-d H:i:s');

                        $model->homeId = $match['homeId'];
                        $model->awayId = $match['awayId'];

                        $teamA = TeamBase::findOne($match['homeId']);
                        if (!empty($teamA->custom_name)) {
                            $model->homeName = $teamA->custom_name;
                        } else {
                            $model->homeName = $match['homeName'];
                        }
                        $teamB = TeamBase::findOne($match['awayId']);
                        if (!empty($teamB->custom_name)) {
                            $model->awayName = $teamB->custom_name;
                        } else {
                            $model->awayName = $match['awayName'];
                        }

                        $model->leagueId = $match['leagueId'];
                        $model->leagueName = $match['leagueName'];

                    } elseif ($model->status <= 0
                        && $model->status == $match['status']
                        && $model->matchTime == $match['matchTime']
                        && $model->homeScore == $match['homeScore']
                        && $model->awayScore == $match['awayScore']
                        && $model->halfStartTime == $match['halfStartTime']
                        && $model->extraExplain == json_encode($match['extraExplain'])
                    ) {
                        continue;
                    }
                    $this->echoLog($count . ' ====> MATCHID: ' . $match['matchId']);
                    //$logger->LogInfo($count . ' #########b MATCHID: ' . $match['matchId']);
                    //$logger->LogInfo($count . 'data: ' . json_encode($match));

                    $model->leagueType = $match['leagueType'];

                    $model->matchTime = $match['matchTime'];
                    $model->extraExplain = json_encode($match['extraExplain']);
                    $model->status = $match['status'];
                    //$model->startTime = isset($match['startTime'])?$match['startTime']:0;
                    $model->halfStartTime = $match['halfStartTime'];
                    $model->homeScore = $match['homeScore'];
                    $model->awayScore = $match['awayScore'];
                    $model->explain = $match['explain'];
                    $model->homeHalfScore = $match['homeHalfScore'];
                    $model->awayHalfScore = $match['awayHalfScore'];
                    $model->homeRed = $match['homeRed'];
                    $model->awayRed = $match['awayRed'];
                    $model->homeYellow = $match['homeYellow'];
                    $model->awayYellow = $match['awayYellow'];
                    $model->homeCorner = $match['homeCorner'];
                    $model->awayCorner = $match['awayCorner'];
                    $model->hasLineup = (isset($match['hasLineup']) && $match['hasLineup'] === true) ? 1 : 0;

                    $model->season = $match['season'];
                    $model->stageId = !empty($match['stageId']) ? $match['stageId'] : 0;
                    $model->round = $match['round'];
                    $model->group = $match['group'];
                    $model->location = $match['location'];
                    $model->weather = $match['weather'];
                    $model->temperature = $match['temperature'];


                    $model->updated_time = date('Y-m-d H:i:s');

                    $model->save(false);
                    $count++;
                }
                $this->echoLog('Total: ' . $count);
            } else {
                //$logger->LogInfo('Response Error: ' . json_encode($matches));
                $this->echoLog('Response Error: ' . json_encode($matches));
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
            $this->echoLog('Exception: ' . $exception->getMessage());
        }
        $this->echoLog('#############################END : ');

    }

    public function actionMatchBeforeWeek()
    {
        $date = Utility::getCurrentDate();
        $time = strtotime($date);
        for ($i = 1; $i < 15; $i++) {
            $dateRun = date('Y-m-d', strtotime('+' . $i . ' day', $time));
            $this->actionMatchLive($dateRun);
        }
    }

    public function actionMatchLeagueHot()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_match_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');

        $leagues = LeagueBase::getHotLeagues();
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                //$logger->LogInfo(' #####b league : ' . $league->league_id);
                $matches = Isport::instance()->getMatchLiveByLeague($league->league_id);
                ////$logger->LogInfo('res league: '.json_encode($leagues) );
                try {
                    if (!empty($matches['data'])) {
                        $count = 1;
                        foreach ($matches['data'] as $match) {

                            $model = MatchBase::findOne($match['matchId']);
                            if (empty($model)) {
                                $model = new MatchBase();
                                $model->matchId = $match['matchId'];
                                $model->created_time = date('Y-m-d H:i:s');
                                $model->homeName = $match['homeName'];
                                $model->awayName = $match['awayName'];
                                $model->homeId = $match['homeId'];
                                $model->awayId = $match['awayId'];
                            }

                            //$logger->LogInfo($count . ' #####b MATCHID: ' . $match['matchId']);
                            //$logger->LogInfo($count . 'data: ' . json_encode($match));

                            $model->leagueId = $match['leagueId'];
                            $model->leagueType = $match['leagueType'];

                            $model->leagueName = $match['leagueName'];
                            $model->matchTime = $match['matchTime'];
                            $model->extraExplain = json_encode($match['extraExplain']);
                            $model->status = $match['status'];
                            //$model->startTime = isset($match['startTime'])?$match['startTime']:0;
                            $model->halfStartTime = $match['halfStartTime'];
                            $model->homeScore = $match['homeScore'];
                            $model->awayScore = $match['awayScore'];
                            $model->explain = $match['explain'];
                            $model->homeHalfScore = $match['homeHalfScore'];
                            $model->awayHalfScore = $match['awayHalfScore'];
                            $model->homeRed = $match['homeRed'];
                            $model->awayRed = $match['awayRed'];
                            $model->homeYellow = $match['homeYellow'];
                            $model->awayYellow = $match['awayYellow'];
                            $model->homeCorner = $match['homeCorner'];
                            $model->awayCorner = $match['awayCorner'];
                            $model->hasLineup = (isset($match['hasLineup']) && $match['hasLineup'] === true) ? 1 : 0;

                            $model->season = $match['season'];
                            $model->stageId = !empty($match['stageId']) ? $match['stageId'] : 0;
                            $model->round = $match['round'];
                            $model->group = $match['group'];
                            $model->location = $match['location'];
                            $model->weather = $match['weather'];
                            $model->temperature = $match['temperature'];


                            $model->updated_time = date('Y-m-d H:i:s');

                            $model->save(false);
                            $count++;
                        }
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                }
            }
        }
    }

    public function actionCountry()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_country_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start get_country ========');
        $leagues = Isport::instance()->getCountry();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($leagues['data'])) {
                $count = 1;
                foreach ($leagues['data'] as $league) {
                    //$logger->LogInfo('----- league: ' . json_encode($league));
                    $model = FbCountryBase::findOne($league['countryId']);
                    if (empty($model)) {
                        $model = new FbCountryBase();
                        $model->countryId = $league['countryId'];

                    }
                    $model->country = $league['country'];

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }

    }


    public function actionStats($date = null)
    {
        if (empty($date)) {
            $time = time();
            $hour = (int)date('H');
            if ($hour >= 0 && $hour < 7) {
                $time = strtotime('-1 day');
            }
            $date = date('Y-m-d', $time);
        }
        echo PHP_EOL . 'Start : ' . date('Y-m-d H:i:s');
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_stats_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start get_stats_ ========');
        $leagues = Isport::instance()->getStats($date);
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($leagues['data'])) {
                $count = 1;
                foreach ($leagues['data'] as $league) {
                    //$logger->LogInfo('----- league: ' . json_encode($league));
                    $model = FbStatsBase::findOne($league['matchId']);
                    if (empty($model)) {
                        $model = new FbStatsBase();
                        $model->matchId = $league['matchId'];
                    }
                    $model->create_date = $date;
                    $model->data = json_encode($league['stats']);

                    $model->save(false);
                    $count++;
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }
        echo PHP_EOL . 'END : ' . date('Y-m-d H:i:s');
    }

    public function actionOdds()
    {
        $this->echoLog(' =========== START : ODDS ===========');
        if (empty($date)) $date = date('Y-m-d');

        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_odds_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start get_stats_ ========');
        $odds = Isport::instance()->getOdds();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($odds['data'])) {
                $count = 0;
                foreach ($odds['data'] as $key => $values) {
                    foreach ($values as $value) {
                        //$logger->LogInfo($key . ': ' . $value);
                        FbOddsBase::updateOdd($key, $value);
                    }
                    $count++;
                }
                $this->echoLog('Total: ' . $count);
            } else {
                $this->echoLog('Response Error: ' . json_encode($odds));
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
            $this->echoLog('Exception: ' . $exception->getMessage());
        }
        $this->echoLog(' ############################END ODDS' . date('Y-m-d H:i:s'));
    }

    public function actionStandings()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        //$leagues = LeagueBase::findAll(['isHot' => 1]);
        $timeUpdate = date('Y-m-d H:i:s', time()-600);
        $statusFinished = MatchBase::STATUS_FINISHED;
        $sql = 'SELECT DISTINCT leagueId FROM fb_matches WHERE `status` = :status AND updated_time >= :timeUpdate';
        $leagueUpdate = \Yii::$app->db->createCommand($sql)
            ->bindParam(':timeUpdate', $timeUpdate)
            ->bindParam(':status', $statusFinished)
            ->queryAll();
        $arrLeagueId = ArrayHelper::getColumn($leagueUpdate, 'leagueId');
        if(empty($arrLeagueId)){
            $this->echoLog(' Not FOUND League update');
            die;
        }
        $leagues = LeagueBase::findAll(['league_id' => $arrLeagueId]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $standings = Isport::instance()->getStandings($league->league_id, $league->type);
                try {
                    if (!empty($standings['data'])) {
                        $standing = $standings['data'];
                        //foreach ($standings['data'] as $standing) {
                        if ($league->type == LeagueBase::TYPE_LEAGUE) {
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $standing['leagueInfo']['leagueId'],
                                'season' => $standing['leagueInfo']['currentSeason'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $standing['leagueInfo']['leagueId'];
                                $model->season = $standing['leagueInfo']['currentSeason'];
                                $model->type = $league->type;
                                $model->slug = $league->slug;
                            }
                            $model->lastUpdateTime = $standing['lastUpdateTime'];
                            $model->standings = json_encode($standing);
                            $model->save(false);
                        } else {
                            //$this->echoLog('standing: '.json_encode($standing));
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $league->league_id,
                                'season' => $standing[0]['season'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $league->league_id;
                                $model->season = $standing[0]['season'];
                                $model->type = $league->type;
                            }
                            $model->lastUpdateTime = time();
                            $model->standings = json_encode($standing[0]);
                            $model->save(false);
                        }

                        // }
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            }
        }
        $this->echoLog(' ################# END ');
    }

    public function actionStandingHotLeague()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $leagues = LeagueBase::findAll(['isHot' => 1]);
        //$leagues = LeagueBase::find()->all();
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $standings = Isport::instance()->getStandings($league->league_id, $league->type);
                try {
                    if (!empty($standings['data'])) {
                        $standing = $standings['data'];
                        //foreach ($standings['data'] as $standing) {
                        if ($league->type == LeagueBase::TYPE_LEAGUE) {
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $standing['leagueInfo']['leagueId'],
                                'season' => $standing['leagueInfo']['currentSeason'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $standing['leagueInfo']['leagueId'];
                                $model->season = $standing['leagueInfo']['currentSeason'];
                                $model->type = $league->type;
                                $model->slug = $league->slug;
                            }
                            $model->lastUpdateTime = $standing['lastUpdateTime'];
                            $model->standings = json_encode($standing);
                            $model->save(false);
                        } else {
                            //$this->echoLog('standing: '.json_encode($standing));
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $league->league_id,
                                'season' => $standing[0]['season'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $league->league_id;
                                $model->season = $standing[0]['season'];
                                $model->type = $league->type;
                            }
                            $model->lastUpdateTime = time();
                            $model->standings = json_encode($standing[0]);
                            $model->save(false);
                        }

                        // }
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            }
        }
        $this->echoLog(' ################# END ');
    }

    public function actionStandingAll()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        //$leagues = LeagueBase::findAll(['isHot' => 1]);
        $timeUpdate = date('Y-m-d 12:00:00', strtotime('-1 day'));
        $statusFinished = MatchBase::STATUS_FINISHED;
        $sql = 'SELECT DISTINCT leagueId FROM fb_matches WHERE `status` = :status AND updated_time >= :timeUpdate';
        $leagueUpdate = \Yii::$app->db->createCommand($sql)
            ->bindParam(':timeUpdate', $timeUpdate)
            ->bindParam(':status', $statusFinished)
            ->queryAll();
        $arrLeagueId = ArrayHelper::getColumn($leagueUpdate, 'leagueId');
        if(empty($arrLeagueId)){
            $this->echoLog(' Not FOUND League update');
            die;
        }
        $leagues = LeagueBase::findAll(['league_id' => $arrLeagueId]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $standings = Isport::instance()->getStandings($league->league_id, $league->type);
                try {
                    if (!empty($standings['data'])) {
                        $standing = $standings['data'];
                        //foreach ($standings['data'] as $standing) {
                        if ($league->type == LeagueBase::TYPE_LEAGUE) {
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $standing['leagueInfo']['leagueId'],
                                'season' => $standing['leagueInfo']['currentSeason'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $standing['leagueInfo']['leagueId'];
                                $model->season = $standing['leagueInfo']['currentSeason'];
                                $model->type = $league->type;
                                $model->slug = $league->slug;
                            }
                            $model->lastUpdateTime = $standing['lastUpdateTime'];
                            $model->standings = json_encode($standing);
                            $model->save(false);
                        } else {
                            //$this->echoLog('standing: '.json_encode($standing));
                            $model = FbStandingsBase::findOne([
                                'leagueId' => $league->league_id,
                                'season' => $standing[0]['season'],
                                'type' => $league->type
                            ]);
                            if (empty($model)) {
                                $model = new FbStandingsBase();
                                $model->leagueId = $league->league_id;
                                $model->season = $standing[0]['season'];
                                $model->type = $league->type;
                            }
                            $model->lastUpdateTime = time();
                            $model->standings = json_encode($standing[0]);
                            $model->save(false);
                        }

                        // }
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            }
        }
        $this->echoLog(' ################# END ');
    }

    public function actionOddOthers()
    {
        $othes = [
            FbOddOtherBase::TYPE_CORRECT_SOCRE,
            FbOddOtherBase::TYPE_NUMBER_OF_GOAL,
            FbOddOtherBase::TYPE_GOAL_ODD_EVEN,
            FbOddOtherBase::TYPE_CORNER_TOTAL,
            FbOddOtherBase::TYPE_HALF_FULL,
            FbOddOtherBase::TYPE_CORNER_HANDICAP,
        ];

        foreach ($othes as $type) {
            $this->echoLog(' ==========>>>  Start Type : ' . $type);
            $this->runOddSOthers($type);
            //die;
        }
    }

    /**
     * get kèo tỷ số
     */
    private function runOddSOthers($type)
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_events_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start match ========');
        $this->echoLog('Start : ' . date('Y-m-d H:i:s'));
        switch ($type) {
            case FbOddOtherBase::TYPE_CORRECT_SOCRE:
                $events = Isport::instance()->getOddScore();
                break;
            case FbOddOtherBase::TYPE_CORNER_HANDICAP:
                $events = Isport::instance()->getOddsCornersHandicap();
                break;
            case FbOddOtherBase::TYPE_CORNER_TOTAL:
                $events = Isport::instance()->getOddsCornersTotal();
                break;
            case FbOddOtherBase::TYPE_GOAL_ODD_EVEN:
                $events = Isport::instance()->getOddsOddEven();
                break;
            case FbOddOtherBase::TYPE_NUMBER_OF_GOAL:
                $events = Isport::instance()->getOddsTotalGoal();
                break;
            case FbOddOtherBase::TYPE_HALF_FULL:
                $events = Isport::instance()->getOddsHalfFull();
                break;
            default:
                break;
        }
        if (empty($events)) {
            $this->echoLog('Type Fail : ');
        }


        //$events = Isport::instance()->getOddScore();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($events['data'])) {
                $count = 0;
                foreach ($events['data'] as $event) {
                    if (empty($event['matchId']) || empty($event['odds'])) continue;
                    $inPlay = 0;
                    $model = FbOddOtherBase::findOne([
                        'matchId' => $event['matchId'],
                        'companyId' => $event['companyId'],
                        'inPlay' => $inPlay,
                        'type' => $type,
                    ]);
                    if (empty($model)) {
                        $model = new FbOddOtherBase();
                        $model->matchId = $event['matchId'];
                        $model->companyId = $event['companyId'];
                        $model->inPlay = $inPlay;
                        $model->type = $type;
                    } elseif ($model->changeTime == $event['changeTime']) {
                        continue;
                    }

                    $model->odds = json_encode($event['odds']);
                    $model->changeTime = json_encode($event['changeTime']);

                    $model->save(false);
                    $count++;
                }
                $this->echoLog('Count : ' . $count);
            } else {
                $this->echoLog('response : ' . json_encode($events));
            }
        } catch (\Exception $exception) {
            $this->echoLog('Exception: ' . $exception->getMessage());
        }
        $this->echoLog('END : ' . date('Y-m-d H:i:s'));
    }

    private function echoLog($message)
    {
        echo PHP_EOL . date('Y-m-d H:i:s') . ' ---> ' . $message;
    }

    public function actionTopscore()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $leagues = LeagueBase::findAll(['isHot' => 1]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $this->echoLog(' -------> name: ' . $league->name);
                //$standings = Isport::instance()->getTopsscore($league->league_id, $league->currentSeason);
                $standings = Isport::instance()->getTopsscore($league->league_id);
                //$this->echoLog(' -----> request : ' . json_encode($standings));
                try {
                    if (!empty($standings['data'])) {
                        $standing = $standings['data'];
                        $this->echoLog(' -----> count : ' . count($standing));
                        //$this->echoLog(' -----> data : ' . json_encode($standing));
                        if(count($standing) <= 1) continue;
                        $model = FbTopscorer::findOne([
                            'leagueId' => $league->league_id,
                            'season' => $league->currentSeason,
                        ]);
                        if (empty($model)) {
                            $model = new FbTopscorer();
                            $model->leagueId = $league->league_id;
                            $model->season = $league->currentSeason;
                        }
                        $model->updated_time = date('Y-m-d H:i:s');
                        $model->contents = json_encode($standing);
                        $model->save(false);
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            }
        }
        $this->echoLog(' ################# END ');
    }

    public function actionTopscoreByLeague($leagueId, $season='')
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $league = LeagueBase::findOne($leagueId);

        if (!empty($league)) {
            if(empty($season)){
                $season = $league->currentSeason;
            }
            //foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $this->echoLog(' -------> name: ' . $league->name);
                //$standings = Isport::instance()->getTopsscore($league->league_id, $league->currentSeason);
                $standings = Isport::instance()->getTopsscore($league->league_id, $season);
                //$this->echoLog(' -----> request : ' . json_encode($standings));
                try {
                    if (!empty($standings['data'])) {
                        $standing = $standings['data'];
                        $this->echoLog(' -----> count : ' . count($standing));
                        //$this->echoLog(' -----> data : ' . json_encode($standing));
                        if(count($standing) > 1) {
                            $model = FbTopscorer::findOne([
                                'leagueId' => $league->league_id,
                                'season' => $season,
                            ]);
                            if (empty($model)) {
                                $model = new FbTopscorer();
                                $model->leagueId = $league->league_id;
                                $model->season = $league->currentSeason;
                            }
                            $model->updated_time = date('Y-m-d H:i:s');
                            $model->contents = json_encode($standing);
                            $model->save(false);
                        }
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            //}
        }
        $this->echoLog(' ################# END ');
    }

    public function actionFifaRanking()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_league_isport_data_' . date('Ymd'), KLogger::INFO);
        //$logger->LogInfo('======== start league ========');
        $leagues = Isport::instance()->getFifaRanking();
        ////$logger->LogInfo('res league: '.json_encode($leagues) );
        try {
            if (!empty($leagues['data'])) {
                $datas = [];
                foreach ($leagues['data'] as $league) {
                    if(empty($league['updateDate'])) continue;
                    $datas[$league['updateDate']][] = $league;
                }

                if(!empty($datas)){
                    foreach ($datas as $key => $values){
                        if(empty($values[0])) continue;
                        $this->echoLog(' updateDate: '.$key);
                        FbFifaRank::deleteAll(['updateDate' => $key]);
                        $atts = array_keys($values[0]);
                        \Yii::$app->db->createCommand()->batchInsert(FbFifaRank::tableName(), $atts, $values)->execute();
                    }
                }
            }
        } catch (\Exception $exception) {
            //$logger->LogInfo('Exception: ' . $exception->getMessage());
        }

    }


    public function actionPlayerstats()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $leagues = LeagueBase::findAll(['isHot' => 1]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $standings = Isport::instance()->getPlayerstats($league->league_id);
                try {
                    if (!empty($standings['data'])) {
                        $standings = $standings['data'];

                        if(empty($standings[0])) continue;
                        $atts = array_keys($standings[0]);
                        $dataInserts = [];
                        foreach ($standings as $standing){
                            if(empty($standing['playerId'])) continue;
                            //if(isset($dataInserts[$standing['playerId']])) continue;
                            $dataInserts[] = $standing;
                        }
                        if(empty($dataInserts)) continue;

                        FbPlayerstat::deleteAll([
                            'leagueId' => $league->league_id,
                        ]);
                        \Yii::$app->db->createCommand()->batchInsert(FbPlayerstat::tableName(), $atts, $dataInserts)->execute();

                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
                sleep(2);
            }
        }
        $this->echoLog(' ################# END ');
    }

    /**
     * get data top scorer 5 mua gan nhat
     */
    public function actionTopscoreTest()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $leagues = LeagueBase::findAll(['isHot' => 1]);
        if (!empty($leagues)) {
            foreach ($leagues as $league) {
                $this->echoLog(' league_id: ' . $league->league_id);
                $currentSeason = $league->currentSeason;
                $explode = explode('-', $currentSeason);
                $type = count($explode);
                for ($i=0; $i < 2; $i++){

                    if($type == 2){
                        $season = ((int)$explode[0] - $i).'-'.((int)$explode[1] - $i);
                    }else{
                        $season = (int)$explode[0] - $i;
                    }
                    $this->echoLog(' -----> $season: ' . $season);

                    $standings = Isport::instance()->getTopsscore($league->league_id, $season);
                    try {
                        if (!empty($standings['data'])) {
                            $standing = $standings['data'];
                            $this->echoLog(' -----> standing: ' . json_encode($standing));
                            if(count($standing) <= 1) continue;
                            $model = FbTopscorer::findOne([
                                'leagueId' => $league->league_id,
                                'season' => $season,
                            ]);
                            if (empty($model)) {
                                $model = new FbTopscorer();
                                $model->leagueId = $league->league_id;
                                $model->season = $season;
                            }
                            $model->updated_time = date('Y-m-d H:i:s');
                            $model->contents = json_encode($standing);
                            $model->save(false);
                        } else {
                            $this->echoLog('Response Error: ' . json_encode($standings));
                        }
                    } catch (\Exception $exception) {
                        //$logger->LogInfo('Exception: ' . $exception->getMessage());
                        $this->echoLog('Exception: ' . $exception->getMessage());
                    }
                }
            }
        }
        $this->echoLog(' ################# END ');
    }

    public function actionPlayer()
    {
        //$logger = new KLogger('logs' . DS.'isports' .DS.'get_team_isport_data_' . date('Ymd'), KLogger::INFO);
        $this->echoLog(' ======================= Start ===============');
        $leagues = ArrayHelper::getColumn(LeagueBase::findAll(['isHot' => 1]), 'league_id');

        if(empty($leagues)) die('Not found league hot');

        $teams = TeamBase::findAll(['leagueId' => $leagues]);
        if (!empty($teams)) {
            foreach ($teams as $team) {
                $this->echoLog(' team_id: ' . $team->teamId);
                $standings = Isport::instance()->getPlayers($team->teamId);
                try {
                    if (!empty($standings['data'])) {
                        $standings = $standings['data'];

                        if(empty($standings[0])) continue;
                        $atts = array_keys($standings[0]);
                        $dataInserts = [];
                        foreach ($standings as $standing){
                            if(empty($standing['playerId'])) continue;
                            $dataInserts[$standing['playerId']] = $standing;
                        }
                        if(empty($dataInserts)) continue;

                        FbPlayer::deleteAll([
                            'teamId' => $team->teamId,
                        ]);
                        $countP = \Yii::$app->db->createCommand()->batchInsert(FbPlayer::tableName(), $atts, $dataInserts)->execute();
                        $this->echoLog(' ---> count: ' . $countP);
                    } else {
                        $this->echoLog('Response Error: ' . json_encode($standings));
                    }
                } catch (\Exception $exception) {
                    //$logger->LogInfo('Exception: ' . $exception->getMessage());
                    $this->echoLog('Exception: ' . $exception->getMessage());
                }
            }
        }
        $this->echoLog(' ################# END ');
    }


    public function actionUpdateSlug(){
        $leagues = LeagueBase::find()->all();
        if (!empty($leagues)) {
            foreach ($leagues as $league){
                $this->echoLog(' league_id: ' . $league->league_id);
                if(empty($league->custom_name)){
                    $league->slug = CFunction::unsignString($league->name);
                }else {
                    $league->slug = CFunction::unsignString($league->custom_name);
                }
                $league->save(false);
                $this->echoLog(' ----> slug: ' . $league->slug);
            }
        }
    }
}