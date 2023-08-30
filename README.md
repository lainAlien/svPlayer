# svPlayer
a sunvox player for ur website

a small and basic sunvox player to embed in your website. it plays back a random or selected sunvox project, with the intent of being used for generative and noisy patches.

###features
- a play/stop button!
- a volume slider!
- color graphics!

###usage
+ place the svPlayer folder wherever in your website's directory structure you want
+ put your .sunvox projects in the `svPlayer/projects` folder
+ edit player.css if you want
+ load svPlayer/player.php as the contents of a div on whatever page you want it on
  + you can use `?p=<project.sunvox>` to load the file `svPlayer/projects/<project.sunvox>`

###todo
* make it stop LFO-driven projects
* make it less ugly
* make it more responsive (probably also less ugly)
