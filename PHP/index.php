<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <title>Resume</title>
    <style>
        .disclaimer {
            font-size: small;
            text-align: center;
            margin-top: 0;
            margin-bottom: 1em;
        }
        .personal-information h1,
        .personal-information h3 {
            text-align: center; /* Center align the text */
            margin: 0; /* Remove default margins */
            padding: 0.25em 0 0 0; /* Add padding only to the top */
        }
        .personal-information {
            border-bottom: 1px solid #ccc; /* Optional: adds a line below the header */
        }
        .professional-summary h2 {
            text-align: center;
            margin: 0;
            padding: 0.25em 0 0 0; /* Add padding only to the top */
        }
        .professional-experience h2 {
            text-align: center;
            margin: 0;
            padding: 0.25em 0 0 0; /* Add padding only to the top */
        }
        .education h2 {
            text-align: center;
            margin: 0;
            padding: 0.25em 0 0 0; /* Add padding only to the top */
        }
        .skills h2 {
            text-align: center;
            margin: 0;
            padding: 0.25em 0 0 0; /* Add padding only to the top */
        }
    </style>
</head>
<body>
    <?php
    echo '<div class="disclaimer">This website was created using PHP, Apache, MySQL, phpMyAdmin, and Docker.</div>';
    ?>
    <?php
    $connect = mysqli_connect(
        'db', // service name
        getenv('MYSQL_USER'), // username from environment variable
        getenv('MYSQL_PASSWORD'), // password from environment variable
        getenv('MYSQL_DATABASE') // database name
    );
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch Personal Information
    $query = "SELECT * FROM PersonalInformation";
    if ($response = mysqli_query($connect, $query)) {
        while ($i = mysqli_fetch_assoc($response)) {
            ?>
            <header class='personal-information'>
                <h1><?= $i['FullName'] ?></h1>
                <h3><?= $i['Title'] ?> &middot; <?= $i['Location'] ?> &middot; <?= $i['PhoneNumber'] ?> &middot; <?= $i['Email'] ?> &middot; <?= $i['LinkedIn'] ?></h3>
                <hr>
            </header>
            <?php
        }
    }

    // Fetch Professional Summary
    $query_summary = "SELECT * FROM ProfessionalSummary";
    if ($response_summary = mysqli_query($connect, $query_summary)) {
        while ($summary = mysqli_fetch_assoc($response_summary)) {
            ?>
            <section class='professional-summary'>
                <h2>Professional Summary</h2>
                <p><?= $summary['Summary'] ?></p>
                <hr>
            </section>
            <?php
        }
    }

    // Fetch Work Experience
    ?>
    <section class='professional-experience'>
        <h2>Professional Experience</h2>
        <?php
        $query_work = "SELECT * FROM Experience";
        if ($response_work = mysqli_query($connect, $query_work)) {
            while ($work = mysqli_fetch_assoc($response_work)) {
                ?>
                <div class='work-experience'>
                    <h3><?= $work['Title'] ?></h3>
                    <p><?= $work['Company'] ?> &middot; <?= $work['Location'] ?> &middot; <?= $work['StartDate'] ?> - <?= $work['EndDate'] ? $work['EndDate'] : 'Present' ?></p>
                    <p><?= $work['Description'] ?></p>
                </div>
                <?php
            }
        }
        ?>
        <hr>
    </section>
    <?php

    // Fetch Education
    ?>
    <section class='education'>
        <h2>Education</h2>
        <?php
        $query_education = "SELECT * FROM Education";
        if ($response_education = mysqli_query($connect, $query_education)) {
            while ($education = mysqli_fetch_assoc($response_education)) {
                ?>
                <div class='education-experience'>
                    <h3><?= $education['Institution'] ?> &middot; <?= $education['Degree'] ?> &middot; <?= $education['FieldOfStudy'] ?></h3>
                    <p><?= $education['Location'] ?> &middot; <?= $education['StartDate'] ?> - <?= $education['EndDate'] ?></p>
                    <p><?= $education['Description'] ?></p>
                </div>
                <?php
            }
        }
        ?>
        <hr>
    </section>
    <?php

    // Fetch Skills
    ?>
    <section class='skills'>
        <h2>Skills</h2>
        <div class='skills-list'>
            <?php
            $query_categories = "SELECT DISTINCT Category FROM Skills ORDER BY Category";
            if ($response_categories = mysqli_query($connect, $query_categories)) {
                while ($category = mysqli_fetch_assoc($response_categories)) {
                    ?>
                    <h3><?= $category['Category'] ?></h3>
                    <ul>
                        <?php
                        $query_skills = "SELECT SkillName FROM Skills WHERE Category = '".mysqli_real_escape_string($connect, $category['Category'])."'";
                        $skillsArray = []; // Initialize an empty array to store skill names
                        if ($response_skills = mysqli_query($connect, $query_skills)) {
                            while ($skills = mysqli_fetch_assoc($response_skills)) {
                                $skillsArray[] = $skills['SkillName']; // Add each skill name to the array
                            }
                            echo implode(', ', $skillsArray); // Display skill names separated by commas
                        }
                        ?>
                    </ul>
                    <?php
                }
            }
            ?>
        </div>
    </section>
    <?php
    mysqli_close($connect);
    ?>
</body>
</html>