<!-- 
* Open Video Hosting Project Main Page
* Version: 10a (June 23rd 2024)
*
* Note that some stuff such as donation and database control either have empty or placeholder values.
* It is up to the hoster of this Open page to control how these work and will need to fill in these
* values with their correct data. See HOSTING.MD for more information.
*
* Originally written by Daniel B. (better known as Pineconium) ;-)
-->


<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');

/* For the newest videos section */
$currentDateandTime=date('Y-m-d H:i:s');

/* Fetch videos from the database, it looks weird but shut up. 
Depending on how your SQL database is handelled, you may need to
change some stuff up, like table/column names
*/
$videoQuery="
    SELECT
        videos.title,
        videos.filepath,
        videos.thumbnailpath,
        videos.creationdate,
        videos.vidlength,
        videos.views,
        users.username
    FROM
        videos
    INNER JOIN
        users
    ON
        videos.user_id=user.id
    ORDER BY
        videos.crcreationdate DESC
";
$regResult=$con->query($videoQuery);

/* and do the same for more popular videos */
$queryTopVideos = "
    SELECT 
        videos.title, 
        videos.filepath, 
        videos.thumbnailpath, 
        videos.creationdate, 
        videos.vidlength,
        videos.views,
        users.username 
    FROM 
        videos 
    INNER JOIN 
        users 
    ON 
        videos.user_id = users.id 
    WHERE 
        WEEK(videos.upload_time) = WEEK('$currentDateandTime') 
    ORDER BY 
        videos.views DESC
    LIMIT 3
";

$topvidresult=$con->query($queryTopVideos);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Open &#187; Home</title>
        <!-- Styles and Favicon management-->
        <link rel="stylesheet" href="styles.css">
        <link rel="icon" type="image/x-icon" href="images/logos/favicon.png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <!-- Header and Navagation control -->
        <table class="PineconiumLogoSector">
          <thead>
            <tr>
              <th><img src="images/header.gif"></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="navbar">
                  <div class="nav-links">
                    <a href="index.html">Home Page</a>
                    <a href="about.html">About Open</a>
                    <a href="tos.html">Terms of Service</a>
                  </div>
                  <div class="nav-actions">
                    <input type="text" placeholder="Search Openly...">
                    <button>Search!</button>
                    <!-- check if the user is signed in -->
                    <?php if(isset($_SESSION['username'])): ?>  
                        <a href="upload.php">Upload</a>
                        <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        <a href="logout.php">Logut</a>
                    <?php else: ?>
                        <a href="login.html">Login</a>
                        <a href="register.html">Register</a>
                    <?php endif; ?>
                  </div>
              </div>
              </td>
            </tr>
          </tbody>
          </table>

        <!-- Main Layout-->
        <table class="PineconiumTabNav">
          <tbody>
            <tr>
              <td>
                <table class="TopStatusArea">
                  <!-- Fix a rendering issue -->
                  <colgroup>
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                  </colgroup>
                  <thead>
                    <tr>
                      <th>Top Donators</th>
                      <th>Announcements</th>
                      <th>Top Creators</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Add code for the donator segment!</td>
                      <td>
                        <h1 class="announce_title">Testing Announcement</h1>
                        <p>Lorem ipsum</p>
                      </td>
                      <td>Add list sorting functions for the top weekly creators</td>
                    </tr>
                  </tbody>
                </table>

                <!-- Video list area-->
                <table class="TopStatusArea">
                  <thead>
                    <tr>
                      <div class="title-container">
                        <img src="images/icon_recommend.png" height="24px" width="24px"><h1 class="table_title">Recommended For You</h1>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <!-- TODO: Make a basic algorithm for recommended videos-->
                      <td>
                        <?php if ($result->num_rows > 0): ?>
                          <?php while($row = $result->fetch_assoc()): ?>
                            <div class="video-container">
                                <div class="video-thumbnail">
                                    <img src="<?php echo htmlspecialchars($row['thumbnailpath']); ?>" alt="Thumbnail">
                                </div>
                                <div class="video-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="video-info">
                                    by: <?php echo htmlspecialchars($row['username']); ?> / <?php echo htmlspecialchars($row['duration']); ?> mins / <?php echo htmlspecialchars($row['views']); ?> views
                                </div>
                            </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No videos found. Maybe try <a href="index.php">refreshing</a> or searching?</p>
                        <?php endif; ?>
                      </td>
                    </tr>
                  </tbody>
                <table class="TopStatusArea">
                    <thead>
                      <tr>
                        <div class="title-container">
                          <img src="images/icon_newvideos.png" height="24px" width="24px"><h1 class="table_title">Newest Videos</h1>
                        </div>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <!-- To save stuff like updating time, this should perferably be updated every 25 mins.-->
                        <td>
                          <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <div class="video-container">
                                    <div class="video-thumbnail">
                                        <img src="<?php echo htmlspecialchars($row['thumbnailpath']); ?>" alt="Thumbnail">
                                    </div>
                                    <div class="video-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div class="video-info">
                                        by: <?php echo htmlspecialchars($row['username']); ?> / <?php echo htmlspecialchars($row['duration']); ?> mins / <?php echo htmlspecialchars($row['views']); ?> views
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No videos found. Maybe try <a href="index.php">refreshing</a> or searching?</p>
                        <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                </table>
                <table class="TopStatusArea">
                  <thead>
                    <tr>
                      <div class="title-container">
                        <img src="images/icon_topvideo.png" height="24px" width="24px"><h1 class="table_title">Top Videos this Week</h1>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <div class="video-container">
                                    <div class="video-thumbnail">
                                        <img src="<?php echo htmlspecialchars($row['thumbnailpath']); ?>" alt="Thumbnail">
                                    </div>
                                    <div class="video-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div class="video-info">
                                        by: <?php echo htmlspecialchars($row['username']); ?> / <?php echo htmlspecialchars($row['duration']); ?> mins / <?php echo htmlspecialchars($row['views']); ?> views
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No videos found. Maybe try <a href="index.php">refreshing</a> or searching?</p>
                            <?php endif; ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="TopStatusArea">
                  <thead>
                    <tr>
                      <div class="title-container">
                        <img src="images/icon_topcreators.png" height="24px" width="24px"><h1 class="table_title">Top Creators this Week</h1>
                      </div>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="creator-container">
                            <div class="creator-thumbnail"><img src="images/defaultpfp.png"></div>
                            <div class="creator-username">Username</div>
                            <div class="creator-info">123k subscribers</div>
                        </div>
                        <div class="creator-container">
                            <div class="creator-thumbnail"><img src="images/defaultpfp.png"></div>
                            <div class="creator-username">Username</div>
                            <div class="creator-info">123k subscribers</div>
                        </div>
                        <div class="creator-container">
                            <div class="creator-thumbnail"><img src="images/defaultpfp.png"></div>
                            <div class="creator-username">Username</div>
                            <div class="creator-info">123k subscribers</div>
                        </div>
                    </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </table>
          <table class="UpdatesSect">
            <!-- Footer -->
            <tfoot>
              <tr>
                  <td><p class="footerText">&copy; Pineconium 2024. All rights reserved. Powered by OpenViHo version 10a</p></td>
              </tr>
              </tfoot>
          </table>
    </body>
</html>