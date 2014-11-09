package fishgenerator.service;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.math.BigDecimal;
import java.net.URISyntaxException;
import java.net.URL;
import java.sql.Connection;
import java.sql.DatabaseMetaData;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Types;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Random;
import java.util.UUID;

import org.springframework.jdbc.datasource.init.ScriptUtils;

import fishgenerator.model.Column;
import fishgenerator.model.ForeignKey;
import fishgenerator.model.Table;
import fishgenerator.util.DbConnector;

public class FishService {

	public void createDatabase(final String name) throws SQLException {
		Connection connection = DbConnector.getConnection();

		Statement stmt = null;

		// create db
		ResultSet resultSet = connection.getMetaData().getCatalogs();

		while (resultSet.next()) {
			// Get the database name, which is at position 1
			String databaseName = resultSet.getString(1);
			if (databaseName.equals(name)) {
				stmt = connection.createStatement();
				stmt.executeUpdate("DROP DATABASE " + name);
				stmt.close();
				break;
			}
		}
		resultSet.close();

		stmt = connection.createStatement();
		stmt.executeUpdate("CREATE DATABASE " + name);
		stmt.close();

		connection.setCatalog(name);
		DbConnector.closeConnection(connection);
	}

	private void loadStructure(final String databaseName,
			final List<String> statements) throws SQLException {
		Connection connection = DbConnector.getConnection(databaseName);

		for (String statement : statements) {
			if (statement.startsWith("CREATE") || statement.startsWith("ALTER")) {
				Statement stmt = connection.createStatement();
				stmt.executeUpdate(statement);
				stmt.close();
			}
		}
		DbConnector.closeConnection(connection);
	}

	private void fillTable(Connection connection, Table table,
			List<Table> tableList) throws SQLException {

		if (!table.isFilled()) {
			for (ForeignKey foreignKey : table.getForeinKeys()) {
				Table pkTable = findTableByName(foreignKey.getPkTableName(),
						tableList);
				if (!pkTable.isFilled()) {
					fillTable(connection, pkTable, tableList);
				} else {
					// Get possible values;
				}
			}

			fillTable(connection, table);
		}
	}

	public static String generateString(Random rng, String characters,
			int length) {
		char[] text = new char[length];
		for (int i = 0; i < length; i++) {
			text[i] = characters.charAt(rng.nextInt(characters.length()));
		}
		return new String(text);
	}

	private void fillTable(Connection connection, Table table)
			throws SQLException {

		StringBuilder inserBuilder = new StringBuilder();
		inserBuilder.append("insert into ");
		inserBuilder.append(table.getName());
		inserBuilder.append(" VALUES");

		List<Column> columns = table.getColumns();
		List<ForeignKey> foreignKeys = table.getForeinKeys();
		Map<String, List<Object>> possibleValuesMap = new HashMap<String, List<Object>>();
		for (ForeignKey foreignKey : foreignKeys) {
			String pkTableName = foreignKey.getPkTableName();
			String pkColumnName = foreignKey.getPkColumnName();

			Statement statement = connection.createStatement();

			ResultSet resultSet = statement.executeQuery("select "
					+ pkColumnName + " from " + pkTableName);

			List<Object> possibleValues = new ArrayList<Object>();

			while (resultSet.next()) {
				Object possibleValue = resultSet.getObject(pkColumnName);
				int type = resultSet.getMetaData().getColumnType(1);
				switch (type) {
				case Types.CHAR:
				case Types.VARCHAR:
				case Types.LONGVARCHAR:
					// TODO: create random string
					possibleValue = "\"" + possibleValue + "\"";
					break;
				default:
					break;
				}
				possibleValues.add(possibleValue);
			}
			possibleValuesMap.put(foreignKey.getFkColumnName(), possibleValues);
		}

		for (int i = 0; i < 100; i++) {
			if (i > 0) {
				inserBuilder.append(",");
			}
			inserBuilder.append("(");
			for (int j = 0; j < columns.size(); j++) {
				if (j > 0) {
					inserBuilder.append(",");
				}

				boolean isForeignKey = false;
				for (ForeignKey foreignKey : foreignKeys) {
					if (foreignKey.getFkColumnName()
							.equals(columns.get(j).name)) {
						isForeignKey = true;
						break;
					}
				}

				Object value = null;
				if (!isForeignKey) {
					switch (columns.get(j).type) {
					case Types.CHAR:
					case Types.VARCHAR:
					case Types.LONGVARCHAR:
						// TODO: create random string
						value = "\"" + UUID.randomUUID().toString() + "\"";
						break;
					case Types.NUMERIC:
					case Types.DECIMAL:
						value = new BigDecimal(Math.random());
						break;
					case Types.BIT:
						value = new Random().nextBoolean();
						// boolean
					case Types.TINYINT:
					case Types.SMALLINT:
						// byte
						value = Math.round(Math.random() * Short.MAX_VALUE);
						break;
					case Types.INTEGER:
						value = Math.round(Math.random() * Integer.MAX_VALUE);
						break;
					// case Types.BIGINT:
					// // long
					// case Types.REAL:
					// // float
					// case Types.FLOAT:
					// case Types.DOUBLE:
					// // double
					case Types.BINARY:
						value = Math.round(Math.random() * Integer.MAX_VALUE);
						// case Types.VARBINARY:
						// case Types.LONGVARBINARY:
						// // byte[]
						// case Types.DATE:
						// // java.sql.Date
						// case Types.TIME:
						// // java.sql.Time
						// case Types.TIMESTAMP:
						// // java.sql.Timestamp
						// case Types.CLOB:
						// // Clob
						// case Types.BLOB:
						// // Blob
						// case Types.ARRAY:
						// // Array
					default:
						value = "";
						break;
					}

					String valueString = value.toString();
					int columnSize = columns.get(j).size;

					if (valueString.toCharArray().length > columnSize) {
						if (columnSize == 0) {
							columnSize = 1;
						}
						if (valueString.endsWith("\"")) {
							valueString = valueString.substring(0,
									columnSize - 1) + "\"";
						} else {
							valueString = valueString.substring(0, columnSize);
						}
					}

					value = valueString;
				} else {
					for (ForeignKey foreignKey : foreignKeys) {
						if (foreignKey.getFkColumnName().equals(
								columns.get(j).name)) {
							List<Object> possibleValues = possibleValuesMap
									.get(foreignKey.getFkColumnName());
							int valueIndex = (int) (Math.random() * possibleValues
									.size());
							value = possibleValues.remove(valueIndex);

							break;
						}
					}
				}

				inserBuilder.append(value);
			}
			inserBuilder.append(")");

		}

		Statement statement = connection.createStatement();
		statement.executeUpdate(inserBuilder.toString());
		table.setFilled(true);
	}

	private void fillColumns(Connection connection) throws SQLException {
		DatabaseMetaData metadata = connection.getMetaData();
		ResultSet columns = metadata.getColumns(null, null, "%", null);
		StringBuilder valuesBuilder = new StringBuilder();

		while (columns.next()) {

			ResultSetMetaData columnsMetadata = columns.getMetaData();
			int columnsNumber = columnsMetadata.getColumnCount();
			for (int i = 1; i <= columnsNumber; i++) {
				System.out.println(i + ",  column property: "

				+ columnsMetadata.getColumnName(i) + ": "
						+ columns.getString(i));
			}
		}
	}

	private void fillDB(String dbName, List<Table> tableList)
			throws SQLException {
		Connection connection = DbConnector.getConnection(dbName);
		for (Table table : tableList) {
			fillTable(connection, table, tableList);
		}
		DbConnector.closeConnection(connection);
	}

	private Table findTableByName(String pkTableName, List<Table> tableList) {
		for (Table table : tableList) {
			if (table.getName().equals(pkTableName)) {
				return table;
			}
		}
		return null;
	}

	private List<String> readSqlScript() throws URISyntaxException, IOException {
		URL url = FishService.class.getClassLoader().getResource(
				"test_dump.sql");
		BufferedReader bufferedReader = new BufferedReader(new FileReader(
				new File(url.toURI())));

		StringBuilder scriptBuilder = new StringBuilder();
		String line;
		while ((line = bufferedReader.readLine()) != null) {
			scriptBuilder.append(line);
			scriptBuilder.append(System.lineSeparator());
		}
		bufferedReader.close();
		List<String> statements = new ArrayList<String>();
		ScriptUtils.splitSqlScript(scriptBuilder.toString(), ';', statements);
		return statements;
	}

	private List<Table> getTableList(String dbName) throws SQLException {
		Connection connection = DbConnector.getConnection(dbName);
		DatabaseMetaData metadata = connection.getMetaData();

		List<Table> tableList = new ArrayList<Table>();

		ResultSet tables = metadata.getTables(null, null, "%", null);
		while (tables.next()) {
			String tableName = tables.getString(3);

			List<ForeignKey> foreignKeys = getForeinKeysByTableName(connection,
					tableName);
			List<Column> columnList = getColumnsByTableName(connection,
					tableName);

			Table table = new Table(tableName, columnList, foreignKeys);
			tableList.add(table);
		}

		DbConnector.closeConnection(connection);
		return tableList;

	}

	private List<Column> getColumnsByTableName(Connection connection,
			String tableName) throws SQLException {
		DatabaseMetaData metadata = connection.getMetaData();
		List<Column> columnList = new ArrayList<Column>();
		// ResultSet columns = metadata.getColumns(null, null, tableName, "%");
		Statement statement = connection.createStatement();
		ResultSet columns = statement
				.executeQuery("Select * from " + tableName);

		// ResultSetMetaData columnsMetadata = columns.getMetaData();
		// int columnsNumber = columnsMetadata.getColumnCount();
		// for (int i = 1; i <= columnsNumber; i++) {
		// System.out.println(i + ",  column property: "
		//
		// + columnsMetadata.getColumnName(i) + ": "
		// + columns.getString(i));
		// }

		// int columnSize = columns.getInt("COLUMN_SIZE");
		// String columnName = columns.getString("COLUMN_NAME");
		// int dataType = columns.getInt("DATA_TYPE");

		ResultSetMetaData columnsMetadata = columns.getMetaData();
		for (int i = 1; i <= columnsMetadata.getColumnCount(); i++) {
			int columnSize = columnsMetadata.getColumnDisplaySize(i);
			String columnName = columnsMetadata.getColumnName(i);
			int dataType = columnsMetadata.getColumnType(i);

			System.out.println(columnName + ", " + columnSize);

			Column column = new Column(columnName, dataType, columnSize);
			columnList.add(column);

		}
		return columnList;
	}

	private List<ForeignKey> getForeinKeysByTableName(Connection connection,
			String tableName) throws SQLException {
		List<ForeignKey> fkList = new ArrayList<ForeignKey>();
		DatabaseMetaData metadata = connection.getMetaData();
		ResultSet importedKeys = metadata.getImportedKeys(
				connection.getCatalog(), null, tableName);
		while (importedKeys.next()) {

			String pkTableName = importedKeys.getString("PKTABLE_NAME");
			String pkColumnName = importedKeys.getString("PKCOLUMN_NAME");
			String fkTableName = importedKeys.getString("FKTABLE_NAME");
			String fkColumnName = importedKeys.getString("FKCOLUMN_NAME");

			ForeignKey foreignKey = new ForeignKey(pkTableName, pkColumnName,
					fkTableName, fkColumnName);
			fkList.add(foreignKey);
		}

		return fkList;
	}

	public static void main(String[] args) throws SQLException,
			URISyntaxException, IOException {
		String dbName = "my_test_db";

		FishService fishService = new FishService();

		List<String> statements = fishService.readSqlScript();
		fishService.createDatabase(dbName);
		fishService.loadStructure(dbName, statements);
		List<Table> tableList = fishService.getTableList(dbName);

		fishService.fillDB(dbName, tableList);
		DbConnector.export(dbName);

	}

}
