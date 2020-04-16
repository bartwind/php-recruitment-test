<?php

namespace Snowdog\DevTest\Model;

use DateTime;
use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    /**
     * Updates last visited page time track
     *
     * @param Page $page
     * @param DateTime $visitTrackTime
     * @return bool
     */
    public function updateVisitTrackTime(Page $page, DateTime $visitTrackTime = null)
    {
        if (is_null($visitTrackTime) || ($visitTrackTime instanceof DateTime) == FALSE) {
            $visitTrackTime = new DateTime();
        }

        /** @var \PDOStatemant $statement */
        $statement = $this->database->prepare(
            'UPDATE pages SET
                `visit_track_time` = :visitTrackTime
            WHERE page_id = :pageId'
        );

        $visitTrackTimeFormatted = $visitTrackTime->format('Y-m-d H:i:s');
        $pageId = $page->getPageId();

        $statement->bindParam(':visitTrackTime', $visitTrackTimeFormatted, \PDO::PARAM_STR);
        $statement->bindParam(':pageId', $pageId, \PDO::PARAM_INT);

        return $statement->execute();
    }
}