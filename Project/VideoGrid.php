<?php
class VideoGrid {

    private $sqlcon, $userLoggedInObj;
    private $largeMode = false;
    private $gridClass = "videoGrid";


    public function __construct($sqlcon, $userLoggedInObj) {
        $this->sqlcon = $sqlcon;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create($videos, $title, $showFilter) {

        if($videos == null) {
            $gridItems = $this->generateItems();
        }
        else {
            $gridItems = $this->generateItemsFromVideos($videos);
        }

        $header = "";

        if($title != null) {
            $header = $this->createGridHeader($title, $showFilter);
        }
        return "$header
                <div class='$this->gridClass'>
                    $gridItems
                </div>";
    }

    public function generateItems() {
        $SQL = "SELECT * FROM media ORDER BY RAND() LIMIT 15";
        $query = $this->sqlcon->query($SQL);

        $elementsHtml = "";
        while($row = $query->fetch_assoc()) {
            $video = new Media($this->sqlcon, $row, $this->userLoggedInObj);
            $item = new VideoGridItem($video, $this->largeMode);
            $elementsHtml = $elementsHtml . $item->create();
        }

        return $elementsHtml;
    }

    public function generateItemsFromVideos($videos) {
        $elementsHtml ="";

        foreach($videos as $video) {
            $item = new VideoGridItem($video, $this->largeMode);
            $elementsHtml .= $item->create();
        }

        return $elementsHtml;
    }

    public function createGridHeader($title, $showFilter) {
        $filter = "";

        if($showFilter) {
            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            
            $urlArray = parse_url($link);
            $query = $urlArray["query"];

            parse_str($query, $params);
            unset($params["orderBy"]);

            $newQuery = http_build_query($params);

            $newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;
            
            $filter = "<div class='right'>
                            <span>|                     Order by:</span>
                            <a href='$newUrl&orderBy=uploadDate'>Upload Date</a>
                            <a href='$newUrl&orderBy=views'>Most Viewed</a>

                        </div>";
        }



        return "<div class='videoGridHeader'>
                        <div class='left'>
                            $title
                        </div>
                        $filter
                   </div>";
    }

    public function createLarge($videos, $title, $showFilter) {
        $this->gridClass .= " large";
        $this->largeMode = true;
        return $this->create($videos, $title, $showFilter);
    }
}

?>