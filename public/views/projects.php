<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/style.css">
    <link rel="stylesheet" type="text/css" href="public/css/projects.css">


    <title>PROJECTS</title>
</head>

<body>
<div class="base-container">
    <nav>
        <img src="public/img/dog.jpg">
        <ul>
            <li>
                <i class="fas fa-project-diagram"></i>
                <a href="#" class="button">projects</a>
            </li>
            <li>
                <i class="fas fa-project-diagram"></i>
                <a href="#" class="button">projects</a>
            </li>
            <li>
                <i class="fas fa-project-diagram"></i>
                <a href="#" class="button">projects</a>
            </li>
            <li>
                <i class="fas fa-project-diagram"></i>
                <a href="#" class="button">projects</a>
            </li>
        </ul>
    </nav>
    <main>
        <header>
            <div class="search-bar">
                <form>
                    <input placeholder="search project">
                </form>
            </div>
            <div class="add-project">
                <i class="fas fa-plus"></i> add project
            </div>
        </header>
        <section class="projects">
            <?php if(isset($projects)) foreach($projects as $project): ?>
                <div id="project-1">
                    <img src="public/uploads/<?= $project->getImage(); ?>">
                    <div>
                        <h2><?= $project->getTitle(); ?></h2>
                        <p><?= $project->getDescription(); ?></p>
                        <div class="social-section">
                            <i class="fas fa-heart"> 600</i>
                            <i class="fas fa-minus-square"> 121</i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
</div>
</body>