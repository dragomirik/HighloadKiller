package fishgenerator.model;

public final class Column {
	public final String name;
	public final int type;
	public final int size;

	public Column(String name, int type, int size) {
		this.name = name;
		this.type = type;
		this.size = size;
	}

}
