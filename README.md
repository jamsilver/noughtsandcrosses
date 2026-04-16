# Noughts and Crosses

## Dependencies

Step 3 onwards requires acpu to be installed:

    pecl install acpu

## Running the code

Run each example by cd-ing into the directory and starting php's internal server, e.g.:

    cd 3-complete-game
    php -S localhost:8080

From step 3 onwards, the move-list is persisted in-memory via acpu. Kill and restart the
server to refresh that store. Note, this is needed for certain types of live code change.
