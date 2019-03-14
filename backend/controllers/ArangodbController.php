<?php 
	namespace backend\controllers;
	use Yii;
	use yii\web\Controller;
	// set up some aliases for less typing later
	use ArangoDBClient\Collection as ArangoCollection;
	use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
	use ArangoDBClient\Connection as ArangoConnection;
	use ArangoDBClient\ConnectionOptions as ArangoConnectionOptions;
	use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
	use ArangoDBClient\Document as ArangoDocument;
	use ArangoDBClient\Exception as ArangoException;
	use ArangoDBClient\Export as ArangoExport;
	use ArangoDBClient\ConnectException as ArangoConnectException;
	use ArangoDBClient\ClientException as ArangoClientException;
	use ArangoDBClient\ServerException as ArangoServerException;
	use ArangoDBClient\Statement as ArangoStatement;
	use ArangoDBClient\UpdatePolicy as ArangoUpdatePolicy;


	class ArangodbController extends Controller{
		public function actionSimplearangodb(){
			// set up some basic connection options
			$connectionOptions = [
    			// database name
    			ArangoConnectionOptions::OPTION_DATABASE => '_system',
    			// server endpoint to connect to
    			ArangoConnectionOptions::OPTION_ENDPOINT => 'tcp://127.0.0.1:8529',
    			// authorization type to use (currently supported: 'Basic')
    			ArangoConnectionOptions::OPTION_AUTH_TYPE => 'Basic',
    			// user for basic authorization
    			ArangoConnectionOptions::OPTION_AUTH_USER => 'root',
    			// password for basic authorization
    			ArangoConnectionOptions::OPTION_AUTH_PASSWD => '',
    			// connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
    			ArangoConnectionOptions::OPTION_CONNECTION => 'Keep-Alive',
    			// connect timeout in seconds
    			ArangoConnectionOptions::OPTION_TIMEOUT => 500,
    			// whether or not to reconnect when a keep-alive connection has timed out on server
    			ArangoConnectionOptions::OPTION_RECONNECT => true,
    			// optionally create new collections when inserting documents
    			ArangoConnectionOptions::OPTION_CREATE => true,
    			// optionally create new collections when inserting documents
    			ArangoConnectionOptions::OPTION_UPDATE_POLICY => ArangoUpdatePolicy::LAST,
			];
			// turn on exception logging (logs to whatever PHP is configured)
			ArangoException::enableLogging();
			$connection = new ArangoConnection($connectionOptions);
			

			// now run another query on the data, using bind parameters
			/*$statement = new ArangoStatement(
				$connection, [
							   'query' => 'FOR u IN @@collection FILTER u.nickName == @nickName RETURN u',
							   'bindVars' => [
								   '@collection' => 'person',
								   'nickName' => 'เอ'
							   ]
						   ]
			);*/
			$statement = new ArangoStatement(
				$connection, ['query' => 'FOR p IN person FOR l IN auditlog FILTER l.person_id == p.id LIMIT 500 RETURN {"person": CONCAT(p.firstName, "  ", p.lastName),"admin": l.username}']
			);
			// executing the statement returns a cursor
			$cursor = $statement->execute();
			// easiest way to get all results returned by the cursor
			var_dump($cursor->getAll());
			exit;
			
		}
	}

?>